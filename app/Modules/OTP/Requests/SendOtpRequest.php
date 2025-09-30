<?php

namespace App\Modules\OTP\Requests;

use App\Modules\OTP\DTOs\SendOtpDtos;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SendOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
        ];
    }

    public function getDto()
    {
        return SendOtpDtos::fromArray($this->validated());
    }
}
