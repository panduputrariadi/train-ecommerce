<?php

namespace App\Modules\OTP\Requests;

use App\Modules\OTP\DTOs\ResetPasswordDto;
use App\Modules\OTP\Rule\FindIdOtp;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string|FindIdOtp|callable>|string>
     */
    public function rules(): array
    {
        $otpId = $this['otp_id'];

        return [
            'otp_id' => ['required', 'integer', new FindIdOtp($otpId)],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

    public function validateDto(): ResetPasswordDto
    {
        return ResetPasswordDto::fromArray($this->validated());
    }
}
