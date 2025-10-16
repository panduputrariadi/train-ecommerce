<?php

namespace App\Modules\Product\Request\Update;

use App\Modules\Product\DTOs\Update\UpdateCategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Validation rules for updating a category
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Return a validated UpdateCategoryDto
     */
    public function validatedDto(): UpdateCategoryDto
    {
        return UpdateCategoryDto::fromArray($this->validated());
    }
}
