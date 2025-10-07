<?php

namespace App\Modules\Product\Request\Update;

use App\Modules\Product\DTOs\Update\UpdateCategoryDto;
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

/**
 * Return a validated UpdateCategoryDto
 *
 * @return UpdateCategoryDto
 */
    public function validatedDto(): UpdateCategoryDto
    {
        return UpdateCategoryDto::fromArray($this->validated());
    }
}
