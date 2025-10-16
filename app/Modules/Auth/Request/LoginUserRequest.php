<?php

namespace App\Modules\Auth\Request;

use App\Modules\Auth\DTOs\LoginUserDto;
use App\Modules\Auth\Rules\ValidUserCredential;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string|ValidUserCredential|callable>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', new ValidUserCredential],
        ];
    }

    public function validatedLogin(): LoginUserDto
    {
        return LoginUserDto::fromArray($this->validated());
    }
}
