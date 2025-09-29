<?php

namespace App\Modules\OTP\Rule;

use App\Modules\OTP\Models\Otp;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MatchesHashedOtp implements Rule
{
    protected int $otpId;

    public function __construct(int $otpId)
    {
        $this->otpId = $otpId;
    }

    public function passes($attribute, $value): bool
    {
        $otpRecord = Otp::find($this->otpId);

        if (! $otpRecord) {
            return false;
        }

        return Hash::check($value, $otpRecord->otp);
    }

    public function message(): string
    {
        return 'The :attribute is invalid.';
    }
}
