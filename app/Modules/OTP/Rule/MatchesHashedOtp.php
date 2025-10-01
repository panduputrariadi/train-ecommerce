<?php

namespace App\Modules\OTP\Rule;

use App\Modules\OTP\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchesHashedOtp implements ValidationRule
{
    protected int $otpId;

    public function __construct(int $otpId)
    {
        $this->otpId = $otpId;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $otpRecord = Otp::find($this->otpId);

        if (! $otpRecord || ! Hash::check($value, $otpRecord->otp)) {
            $fail('The :attribute is invalid.');
        }
    }
}
