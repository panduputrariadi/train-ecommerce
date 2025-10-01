<?php

namespace App\Modules\OTP\Requests;

use App\Modules\OTP\DTOs\ResetPasswordDtos;
use App\Modules\OTP\Rule\FindIdOtp;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        $otpId = $this['otp_id'];
        return [
            'otp_id' => ['required', 'integer', new FindIdOtp($otpId)],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

    public function validateDto()
    {
        return ResetPasswordDtos::fromArray($this->validated());
    }
}
