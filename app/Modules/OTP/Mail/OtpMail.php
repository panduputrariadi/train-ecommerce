<?php

namespace App\Modules\OTP\Mail;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $otp;
    protected string $usedFor;

    public function __construct(string $otp, string $usedFor)
    {
        $this->otp = $otp;
        $this->usedFor = $usedFor;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp'     => $this->otp,
                'usedFor' => $this->usedFor,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
