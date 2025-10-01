<?php

namespace App\Modules\OTP\Actions;

use App\Modules\Auth\Models\User;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\ResetPasswordRequest;

class ResetPasswordAction
{
    public function execute(ResetPasswordRequest $request)
    {
        try {
            $dto = $request->validateDto();
            $otp = Otp::findOrFail($dto->otpId);

            $user = User::where('email', $otp->email)->firstOrFail();
            $user->password = $dto->password;
            $user->save();

            $otp->verified_at = now();
            $otp->save();

        } catch (\Throwable $th) {
            throw $th;
        }
        return $user;
    }
}
