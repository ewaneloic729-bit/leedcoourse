<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Throwable;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $channel = in_array((string) $request->input('channel'), ['email', 'whatsapp'], true)
            ? (string) $request->input('channel')
            : 'email';

        $validated = $request->validate([
            'channel' => ['nullable', 'in:email,whatsapp'],
            'email' => ['nullable', 'email'],
            'whatsapp_phone' => ['nullable', 'string', 'max:25'],
        ]);

        $email = trim((string) ($validated['email'] ?? ''));
        $whatsappPhone = trim((string) ($validated['whatsapp_phone'] ?? ''));

        if ($channel === 'email' && $email === '') {
            return back()->withInput()->withErrors(['email' => 'Veuillez renseigner votre email.']);
        }

        if ($channel === 'whatsapp') {
            if (! Schema::hasColumn('users', 'whatsapp_phone')) {
                return back()->withInput()->withErrors(['whatsapp_phone' => 'Le module WhatsApp n est pas initialise. Lancez les migrations.']);
            }
            if ($whatsappPhone === '') {
                return back()->withInput()->withErrors(['whatsapp_phone' => 'Veuillez renseigner votre numero WhatsApp.']);
            }
        }

        $user = null;
        if ($channel === 'email') {
            $user = User::where('email', $email)->first();
        } else {
            $user = $this->findUserByWhatsappPhone($whatsappPhone);
        }

        $request->session()->put('password_reset_channel', $channel);
        $request->session()->put('password_reset_sent_to', $this->maskDestination($channel, $email, $whatsappPhone));
        $request->session()->put('password_reset_requested_at', now()->timestamp);
        $request->session()->forget('password_reset_whatsapp_url');

        if (! $user) {
            $request->session()->put('password_reset_fake', true);
            $request->session()->forget('password_reset_user_id');

            return redirect()->route('password.code.form')->with('status', 'Si un compte existe, un code temporaire a ete envoye.');
        }

        $request->session()->put('password_reset_fake', false);
        $request->session()->put('password_reset_user_id', $user->id);

        $existingOtp = PasswordResetOtp::where('user_id', $user->id)
            ->where('channel', $channel)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();
        if ($existingOtp && $existingOtp->expires_at->isFuture() && $existingOtp->created_at->gt(now()->subSeconds(60))) {
            return redirect()->route('password.code.form')
                ->with('status', 'Code deja envoye recemment. Reessayez dans quelques instants.');
        }

        $otpCode = $this->generateOtpCode();
        $expiresAt = now()->addMinutes(15);

        PasswordResetOtp::where('user_id', $user->id)
            ->where('channel', $channel)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        PasswordResetOtp::create([
            'user_id' => $user->id,
            'channel' => $channel,
            'code_hash' => Hash::make($otpCode),
            'expires_at' => $expiresAt,
            'attempts' => 0,
        ]);

        try {
            if ($channel === 'email') {
                Mail::to($user->email)->send(new PasswordResetMail((string) $user->name, $otpCode, 15));
            } else {
                $request->session()->put(
                    'password_reset_whatsapp_url',
                    $this->buildWhatsAppUrl((string) $user->whatsapp_phone, (string) $user->name, $otpCode)
                );
            }
        } catch (Throwable $e) {
            Log::error('Password reset code delivery failed.', [
                'channel' => $channel,
                'email' => $user->email ?? $email,
                'whatsapp_phone' => $user->whatsapp_phone ?? $whatsappPhone,
                'exception' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                $channel === 'email' ? 'email' : 'whatsapp_phone' => app()->hasDebugModeEnabled()
                    ? 'Echec d envoi du code temporaire: '.$e->getMessage()
                    : 'Echec d envoi du code temporaire. Verifiez la configuration du canal choisi.',
            ]);
        }

        return redirect()->route('password.code.form')->with('status', 'Si un compte existe, un code temporaire a ete envoye.');
    }

    public function showCodeForm(Request $request)
    {
        if (! $request->session()->has('password_reset_channel')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-code', [
            'channel' => (string) $request->session()->get('password_reset_channel', 'email'),
            'sentTo' => (string) $request->session()->get('password_reset_sent_to', ''),
            'whatsAppUrl' => (string) $request->session()->get('password_reset_whatsapp_url', ''),
        ]);
    }

    public function resetWithCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $userId = (int) $request->session()->get('password_reset_user_id', 0);
        $channel = (string) $request->session()->get('password_reset_channel', '');
        $requestedAt = (int) $request->session()->get('password_reset_requested_at', 0);
        $isFakeRequest = (bool) $request->session()->get('password_reset_fake', false);

        if ($channel === '' || $requestedAt === 0 || now()->timestamp - $requestedAt > (20 * 60)) {
            $request->session()->forget([
                'password_reset_user_id',
                'password_reset_channel',
                'password_reset_fake',
                'password_reset_sent_to',
                'password_reset_requested_at',
                'password_reset_fake_attempts',
                'password_reset_whatsapp_url',
            ]);

            return redirect()->route('password.request')->withErrors(['code' => 'Session expiree. Recommencez la recuperation.']);
        }

        if ($isFakeRequest || $userId === 0) {
            $fakeAttempts = (int) $request->session()->get('password_reset_fake_attempts', 0) + 1;
            $request->session()->put('password_reset_fake_attempts', $fakeAttempts);
            if ($fakeAttempts >= 5) {
                $request->session()->forget([
                    'password_reset_user_id',
                    'password_reset_channel',
                    'password_reset_fake',
                    'password_reset_sent_to',
                    'password_reset_requested_at',
                    'password_reset_fake_attempts',
                    'password_reset_whatsapp_url',
                ]);
            }

            return back()->withErrors(['code' => 'Code invalide.'])->withInput();
        }

        $user = User::find($userId);
        if (! $user) {
            return back()->withErrors(['code' => 'Code invalide.'])->withInput();
        }

        $otp = PasswordResetOtp::where('user_id', $user->id)
            ->where('channel', $channel)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if (! $otp) {
            return back()->withErrors(['code' => 'Code invalide.'])->withInput();
        }

        if ($otp->expires_at->isPast()) {
            $otp->consumed_at = now();
            $otp->save();

            return back()->withErrors(['code' => 'Code expire. Demandez un nouveau code.'])->withInput();
        }

        if ($otp->attempts >= 5) {
            $otp->consumed_at = now();
            $otp->save();

            return back()->withErrors(['code' => 'Trop de tentatives. Demandez un nouveau code.'])->withInput();
        }

        if (! Hash::check((string) $request->input('code'), $otp->code_hash)) {
            $otp->increment('attempts');

            return back()->withErrors(['code' => 'Code invalide.'])->withInput();
        }

        $otp->consumed_at = now();
        $otp->save();
        PasswordResetOtp::where('user_id', $user->id)
            ->where('channel', $channel)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $user->password = Hash::make((string) $request->input('password'));
        $user->remember_token = Str::random(60);
        $user->save();
        event(new PasswordReset($user));

        $request->session()->forget([
            'password_reset_user_id',
            'password_reset_channel',
            'password_reset_fake',
            'password_reset_sent_to',
            'password_reset_requested_at',
            'password_reset_fake_attempts',
            'password_reset_whatsapp_url',
        ]);

        return redirect()->route('login')->with('status', 'Mot de passe reinitialise avec succes.');
    }

    private function generateOtpCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function findUserByWhatsappPhone(string $rawPhone): ?User
    {
        $needle = $this->normalizePhone($rawPhone);
        if ($needle === '') {
            return null;
        }

        // 1) Correspondance exacte normalisee.
        $exact = User::where('whatsapp_phone', $needle)->first();
        if ($exact) {
            return $exact;
        }

        // 2) Fallback tolerant: compare les suffixes (avec/sans indicatif pays).
        return User::query()
            ->whereNotNull('whatsapp_phone')
            ->get()
            ->first(function (User $user) use ($needle) {
                $stored = $this->normalizePhone((string) $user->whatsapp_phone);
                if ($stored === '') {
                    return false;
                }

                if ($stored === $needle) {
                    return true;
                }

                return strlen($stored) >= 8
                    && strlen($needle) >= 8
                    && (str_ends_with($stored, $needle) || str_ends_with($needle, $stored));
            });
    }

    private function normalizePhone(string $value): string
    {
        $phone = preg_replace('/\D+/', '', $value) ?? '';
        if (str_starts_with($phone, '00')) {
            $phone = substr($phone, 2);
        }

        return $phone;
    }

    private function maskDestination(string $channel, string $email, string $whatsappPhone): string
    {
        if ($channel === 'whatsapp') {
            $phone = $this->normalizePhone($whatsappPhone);
            if (strlen($phone) <= 4) {
                return '****';
            }

            return str_repeat('*', max(0, strlen($phone) - 4)).substr($phone, -4);
        }

        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return '***';
        }
        $name = $parts[0];
        $domain = $parts[1];
        $maskedName = strlen($name) <= 2 ? substr($name, 0, 1).'*' : substr($name, 0, 2).str_repeat('*', max(1, strlen($name) - 2));

        return $maskedName.'@'.$domain;
    }

    private function buildWhatsAppUrl(string $rawPhone, string $recipientName, string $otpCode): string
    {
        $phone = $this->normalizePhone($rawPhone);
        $message = rawurlencode(
            "Bonjour {$recipientName},\n\n".
            "Voici votre code de verification LEEDCOURSE : {$otpCode}\n".
            "Ce code expire dans 15 minutes.\n\n".
            "Utilisez-le sur la page de reinitialisation du mot de passe."
        );

        return 'https://wa.me/'.$phone.'?text='.$message;
    }
}
