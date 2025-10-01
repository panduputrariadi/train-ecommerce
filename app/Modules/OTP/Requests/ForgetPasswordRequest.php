<?php

namespace App\Modules\OTP\Requests;

use App\Modules\OTP\DTOs\ForgetPasswordDtos;
use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'otp_id' => 'nullable|integer',
        ];
    }

    public function validatedDto()
    {
        return ForgetPasswordDtos::fromArray($this->validated());
    }
}
