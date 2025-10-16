<?php

namespace App\Modules\OTP\Requests;

use App\Modules\OTP\DTOs\ForgetPasswordDto;
use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'otp_id' => 'nullable|integer',
        ];
    }

    public function validatedDto(): ForgetPasswordDto
    {
        return ForgetPasswordDto::fromArray($this->validated());
    }
}
