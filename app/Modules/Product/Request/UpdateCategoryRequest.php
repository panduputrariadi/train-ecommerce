<?php

namespace App\Modules\Product\Request;

use App\Modules\Product\DTOs\UpdateCategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function validatedDto(): UpdateCategoryDto
    {
        return UpdateCategoryDto::fromArray($this->validated());
    }
}
