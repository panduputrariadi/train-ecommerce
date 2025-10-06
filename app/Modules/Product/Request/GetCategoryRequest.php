<?php

namespace App\Modules\Product\Request;

use App\Modules\Product\DTOs\GetCategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class GetCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer'],
        ];
    }

    public function validatedDto(): GetCategoryDto
    {
        return GetCategoryDto::fromArray($this->validated());
    }
}
