<?php

namespace App\Modules\OTP\Jobs;

use App\Modules\OTP\Mail\ForgetPaswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordJobs implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $email;
    public string $otpCode;

    public function __construct(string $email, string $otpCode)
    {
        $this->email = $email;
        $this->otpCode = $otpCode;
    }

    public function handle():void
    {
        Mail::to($this->email)->send(new ForgetPaswordMail($this->otpCode));
    }
}
