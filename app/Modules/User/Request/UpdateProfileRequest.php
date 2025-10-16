<?php

namespace App\Modules\User\Request;

use App\Modules\User\DTOs\UpdateProfileDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }

    public function validatedDto(): UpdateProfileDto
    {
        return UpdateProfileDto::fromArray($this->validated());
    }
}
