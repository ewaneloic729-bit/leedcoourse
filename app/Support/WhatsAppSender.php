<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class WhatsAppSender
{
    public function sendResetCode(string $rawPhone, string $code): array
    {
        $baseUrl = rtrim((string) config('services.whatsapp.api_base_url'), '/');
        $phoneNumberId = (string) config('services.whatsapp.phone_number_id');
        $token = (string) config('services.whatsapp.access_token');

        if ($baseUrl === '' || $phoneNumberId === '' || $token === '') {
            return ['ok' => false, 'message' => 'Configuration WhatsApp incomplete.'];
        }

        $to = preg_replace('/\D+/', '', $rawPhone ?? '');
        if (! $to) {
            return ['ok' => false, 'message' => 'Numero WhatsApp invalide.'];
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($baseUrl.'/'.$phoneNumberId.'/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => "LEEDCOURSE\nVotre code de reinitialisation est: {$code}\nCe code expire dans 15 minutes.",
                ],
            ]);

        if ($response->successful()) {
            return ['ok' => true, 'message' => 'Code WhatsApp envoye.'];
        }

        $error = $response->json('error.message') ?? $response->body();

        return ['ok' => false, 'message' => 'Envoi WhatsApp echoue: '.$error];
    }

    public function sendTemporaryPassword(string $rawPhone, string $temporaryPassword): array
    {
        $baseUrl = rtrim((string) config('services.whatsapp.api_base_url'), '/');
        $phoneNumberId = (string) config('services.whatsapp.phone_number_id');
        $token = (string) config('services.whatsapp.access_token');

        if ($baseUrl === '' || $phoneNumberId === '' || $token === '') {
            return ['ok' => false, 'message' => 'Configuration WhatsApp incomplete.'];
        }

        $to = preg_replace('/\D+/', '', $rawPhone ?? '');
        if (! $to) {
            return ['ok' => false, 'message' => 'Numero WhatsApp invalide.'];
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($baseUrl.'/'.$phoneNumberId.'/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => "LEEDCOURSE\nVotre nouveau mot de passe temporaire: {$temporaryPassword}\nConnectez-vous puis changez-le dans Mon profil.",
                ],
            ]);

        if ($response->successful()) {
            return ['ok' => true, 'message' => 'Message WhatsApp envoye.'];
        }

        $error = $response->json('error.message') ?? $response->body();

        return ['ok' => false, 'message' => 'Envoi WhatsApp echoue: '.$error];
    }
}
