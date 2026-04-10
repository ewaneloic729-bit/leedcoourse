<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class EnseignantRegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register-choice', ['selectedProfile' => 'enseignant']);
    }

    public function store(Request $request)
    {
        if (PlatformSetting::bool('registrations_open', true) === false) {
            return back()->withInput()->withErrors(['nom' => 'Les inscriptions sont temporairement fermees par l administration.']);
        }

        $request->merge([
            'whatsapp_phone' => $this->normalizePhone((string) $request->input('whatsapp_phone')),
        ]);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:enseignants'],
            'whatsapp_phone' => ['required', 'string', 'min:8', 'max:25', 'unique:users,whatsapp_phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'specialite' => ['required', 'string', 'max:255'],
            'diplome' => ['required', 'string', 'max:255'],
            'annees_experience' => ['required', 'integer', 'min:0'],
        ]);

        $user = null;

        DB::transaction(function () use ($validated, &$user) {
            $fullName = trim($validated['prenom'] . ' ' . $validated['nom']);

            $user = User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'whatsapp_phone' => $validated['whatsapp_phone'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_ENSEIGNANT,
                'is_active' => true,
            ]);

            Enseignant::create([
                'user_id' => $user->id,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'specialite' => $validated['specialite'],
                'diplome' => $validated['diplome'],
                'annees_experience' => $validated['annees_experience'],
            ]);
        });

        $this->sendMatriculeEmail($user);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();
        $user->loadMissing('roleEntity.permissions');
        $request->session()->put('role', $user->role);
        $request->session()->put('permissions', $user->permissionNames());
        $request->session()->put('dashboard_route', $user->dashboardRouteName());

        return redirect()->route('dashboard');
    }

    private function sendMatriculeEmail(User $user): void
    {
        if (empty($user->matricule)) {
            return;
        }

        try {
            Mail::raw(
                "Bienvenue sur LEEDCOURSE.\n\nVotre matricule utilisateur est : {$user->matricule}\n\nConservez ce matricule. Il peut etre demande par l administration.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Votre matricule LEEDCOURSE');
                }
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function normalizePhone(string $value): string
    {
        $phone = preg_replace('/\D+/', '', $value) ?? '';
        if (str_starts_with($phone, '00')) {
            $phone = substr($phone, 2);
        }

        return $phone;
    }
}
