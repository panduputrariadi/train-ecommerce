<?php

namespace App\Modules\OTP\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\OTP\Mail\OtpMail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailJobs implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $email;
    public string $otpCode;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $otpCode)
    {
        $this->email = $email;
        $this->otpCode = $otpCode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new OtpMail($this->otpCode));
    }
}
