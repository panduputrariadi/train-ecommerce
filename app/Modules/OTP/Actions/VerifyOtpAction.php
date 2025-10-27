<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\VerifyOtpRequest;

class VerifyOtpAction
{
    public function execute(VerifyOtpRequest $request): Otp
    {
        $dto = $request->validateVerifyDto();
        $otp = Otp::findOrFail($dto->id);
        $otp->update([
            'verified_at' => now(),
        ]);
        $otp->delete();

        return $otp;
    }
}
