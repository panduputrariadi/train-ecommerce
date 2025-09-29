<?php

namespace App\Modules\OTP\Rule;

use App\Modules\OTP\Models\Otp;
use Illuminate\Contracts\Validation\Rule;

class FindIdOtp implements Rule
{
    protected int $otpId;
    public function __construct(int $otpId)
    {
        $this->otpId = $otpId;
    }
    public function passes($attribute, $value): bool
    {
        $otpIdRecord = Otp::where('id', $this->otpId)
            ->whereNull('verified_at')
            ->where('expired_at', '>', now())
            ->first();

        if (! $otpIdRecord) {
            return false;
        }
        return true;
    }

    public function message(): string
    {
        return 'The :attribute is invalid.';
    }
}
