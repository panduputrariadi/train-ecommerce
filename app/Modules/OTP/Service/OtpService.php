<?php

namespace App\Modules\OTP\Service;

use App\Modules\OTP\Enum\TypeOtp;
use App\Modules\OTP\Enum\UseOtp;
use App\Modules\OTP\Jobs\SendOtpEmailJob;
use App\Modules\OTP\Models\Otp;

class OtpService
{
    public function generateOtp(string $email, UseOtp $usedFor): Otp
    {
        $otpCode = random_int(100000, 999999);

        $otp = Otp::create([
            'email'      => $email,
            'otp'        => $otpCode,
            'type'       => TypeOtp::TYPE_EMAIL,
            'used_for'   => $usedFor,
            'expired_at' => now()->addMinutes(5),
        ]);

        SendOtpEmailJob::dispatch($email, $otpCode, $usedFor->value);

        return $otp;
    }
}
