<?php

namespace App\Modules\Product\Request\Create;

use App\Modules\Product\DTOs\Create\CreateCategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Validation rules for creating a category
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:categories,name'],
            'description' => ['required', 'string'],
        ];
    }

    /**
     * Return a validated CreateCategoryDto
     */
    public function validatedDto(): CreateCategoryDto
    {
        return CreateCategoryDto::fromArray($this->validated());
    }
}
