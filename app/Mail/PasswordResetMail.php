<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientName;
    public string $otpCode;
    public int $expiresInMinutes;

    public function __construct(string $recipientName, string $otpCode, int $expiresInMinutes = 15)
    {
        $this->recipientName = $recipientName;
        $this->otpCode = $otpCode;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    public function build()
    {
        return $this->subject('Code de verification LEEDCOURSE')
            ->view('emails.password-reset');
    }
}
