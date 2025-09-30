<?php

namespace App\Modules\OTP\Rule;

use App\Modules\OTP\Models\Otp;
use Illuminate\Contracts\Validation\ValidationRule;

class FindIdOtp implements ValidationRule
{
    protected int $otpId;

    public function __construct(int $otpId)
    {
        $this->otpId = $otpId;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $otpIdRecord = Otp::find($this->otpId);

        if (! $otpIdRecord) {
            $fail('id not found');
            return;
        }

        if ($otpIdRecord->verified_at !== null) {
            $fail('id already used');
            return;
        }

        if ($otpIdRecord->expired_at <= now()) {
            $fail('id expired');
            return;
        }
    }
}
