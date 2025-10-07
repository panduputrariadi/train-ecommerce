<?php

namespace App\Modules\Product\Request\Read;

use App\Modules\Product\DTOs\Read\GetCategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class GetCategoryRequest extends FormRequest
{
    /**
     * Return validation rules for the GetCategoryRequest
     *
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer'],
        ];
    }

    /**
     * Return a validated GetCategoryDto
     *
     * @return GetCategoryDto
     */
    public function validatedDto(): GetCategoryDto
    {
        return GetCategoryDto::fromArray($this->validated());
    }
}
