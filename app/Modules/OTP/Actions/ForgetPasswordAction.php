<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\Jobs\ForgetPasswordJobs;
use App\Modules\OTP\Enum\TypeOtp;
use App\Modules\OTP\Enum\UseOtp;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\ForgetPasswordRequest;

class ForgetPasswordAction
{
    public function execute(ForgetPasswordRequest $request)
    {
        try {
            $dto = $request->validatedDto();
            $otpCode = random_int(100000, 999999);

            $otp = Otp::create([
                'email'      => $dto->email,
                'otp'        => $otpCode,
                'type'       => TypeOtp::TYPE_EMAIL,
                'used_for'   => UseOtp::USED_FOR_FORGOT_PASSWORD,
                'expired_at' => now()->addMinutes(5),
            ]);
            ForgetPasswordJobs::dispatch($dto->email, $otpCode);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $otp;
    }
}
