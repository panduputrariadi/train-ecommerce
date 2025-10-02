<?php

namespace App\Modules\OTP\Jobs;

use App\Modules\OTP\Mail\OtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as FoundationQueueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOtpEmailJob implements ShouldQueue
{
    use FoundationQueueable, SerializesModels;

    public string $email;
    public string $otpCode;
    public string $usedFor;

    public function __construct(string $email, string $otpCode, string $usedFor)
    {
        $this->email = $email;
        $this->otpCode = $otpCode;
        $this->usedFor = $usedFor;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new OtpMail($this->otpCode, $this->usedFor));
    }
}
