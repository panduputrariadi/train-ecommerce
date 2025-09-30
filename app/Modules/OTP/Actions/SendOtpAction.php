<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\Enum\TypeOtp;
use App\Modules\OTP\Enum\UseOtp;
use App\Modules\OTP\Jobs\SendEmailJobs;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\SendOtpRequest;

class SendOtpAction
{
    public function execute(SendOtpRequest $request): Otp
    {
        try {
            $otpCode = random_int(100000, 999999);
            $dto = $request->getDto();

            $otp = Otp::create([
                'email'      => $dto->email,
                'otp'        => $otpCode,
                'type'       => TypeOtp::TYPE_EMAIL,
                'used_for'   => UseOtp::USED_FOR_REGISTER,
                'expired_at' => now()->addMinutes(5),
            ]);

            SendEmailJobs::dispatch($dto->email, $otpCode);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $otp;
    }
}
