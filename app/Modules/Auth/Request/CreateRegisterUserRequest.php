<?php

namespace App\Modules\Auth\Request;

use App\Modules\Auth\DTOs\CreateRegisterUserDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateRegisterUserRequest extends FormRequest
{
    /**
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
            'otp_id' => ['required', 'exists:otps,id'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'phone' => ['required', 'string', 'max:255'],
        ];
    }

    public function validatedDto(): CreateRegisterUserDto
    {
        return CreateRegisterUserDto::fromArray($this->validated());
    }
}
