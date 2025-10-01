<?php

namespace App\Modules\OTP\Mail;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPaswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Forget Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ForgetPassword',
            with: ['otp' => $this->otp],
        );
    }

    public function attachments(): array
    {
        return [];
    }

}
