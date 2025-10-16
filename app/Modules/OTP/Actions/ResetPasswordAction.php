<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\ResetPasswordRequest;
use App\Modules\Share\Models\User;

class ResetPasswordAction
{
    public function execute(ResetPasswordRequest $request): User
    {
        $dto = $request->validateDto();
        $otp = Otp::findOrFail($dto->otpId);

        $user = User::where('email', $otp->email)->firstOrFail();
        $user->password = $dto->password;
        $user->save();

        $otp->verified_at = now();
        $otp->save();

        return $user;
    }
}
