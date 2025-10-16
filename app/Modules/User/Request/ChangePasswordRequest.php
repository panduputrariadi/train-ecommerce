<?php

namespace App\Modules\User\Request;

use App\Modules\User\DTOs\ChangePasswordDto;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6'],
            'new_password_confirmation' => ['required', 'string', 'min:6', 'same:new_password'],
        ];
    }

    public function validatedDto(): ChangePasswordDto
    {
        return ChangePasswordDto::fromArray($this->validated());
    }
}
