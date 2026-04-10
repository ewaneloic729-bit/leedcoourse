<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_code_can_be_requested_by_email()
    {
        $user = User::factory()->create();
        Mail::fake();

        $response = $this->post('/forgot-password', [
            'channel' => 'email',
            'email' => $user->email,
        ]);

        $response->assertRedirect('/reset-code');
        $this->assertDatabaseHas('password_reset_otps', [
            'user_id' => $user->id,
            'channel' => 'email',
            'consumed_at' => null,
        ]);
        Mail::assertSent(\App\Mail\PasswordResetMail::class);
    }

    public function test_reset_password_code_can_be_requested_by_whatsapp()
    {
        $user = User::factory()->create([
            'whatsapp_phone' => '237612345678',
        ]);

        $response = $this->post('/forgot-password', [
            'channel' => 'whatsapp',
            'whatsapp_phone' => '237612345678',
        ]);

        $response->assertRedirect('/reset-code');
        $response->assertSessionHas('password_reset_whatsapp_url', function ($value) {
            return str_starts_with((string) $value, 'https://wa.me/237612345678?text=');
        });
        $this->assertDatabaseHas('password_reset_otps', [
            'user_id' => $user->id,
            'channel' => 'whatsapp',
            'consumed_at' => null,
        ]);
    }

    public function test_reset_code_screen_can_be_rendered_with_session_context()
    {
        $response = $this->withSession([
            'password_reset_channel' => 'whatsapp',
            'password_reset_sent_to' => '******5678',
            'password_reset_requested_at' => now()->timestamp,
            'password_reset_fake' => true,
            'password_reset_whatsapp_url' => 'https://wa.me/237612345678?text=code',
        ])->get('/reset-code');

        $response->assertStatus(200);
        $response->assertSee('Ouvrir WhatsApp');
    }

    public function test_password_can_be_reset_with_valid_otp_code()
    {
        $user = User::factory()->create();

        PasswordResetOtp::create([
            'user_id' => $user->id,
            'channel' => 'email',
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(15),
            'attempts' => 0,
        ]);

        $response = $this->withSession([
            'password_reset_user_id' => $user->id,
            'password_reset_channel' => 'email',
            'password_reset_requested_at' => now()->timestamp,
            'password_reset_fake' => false,
            'password_reset_sent_to' => 'te***@example.test',
        ])->post('/reset-code', [
            'code' => '123456',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/login');
        $this->assertTrue(Hash::check('Password123!', $user->fresh()->password));
    }

    public function test_invalid_otp_increments_attempt_counter()
    {
        $user = User::factory()->create();

        $otp = PasswordResetOtp::create([
            'user_id' => $user->id,
            'channel' => 'email',
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(15),
            'attempts' => 0,
        ]);

        $response = $this->withSession([
            'password_reset_user_id' => $user->id,
            'password_reset_channel' => 'email',
            'password_reset_requested_at' => now()->timestamp,
            'password_reset_fake' => false,
            'password_reset_sent_to' => 'te***@example.test',
        ])->post('/reset-code', [
            'code' => '654321',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertSame(1, $otp->fresh()->attempts);
    }
}
