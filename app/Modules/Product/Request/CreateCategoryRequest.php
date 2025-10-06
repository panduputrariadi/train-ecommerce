<?php

namespace App\Modules\Product\Request;

use App\Modules\Product\DTOs\CreateCategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }

    public function validatedDto(): CreateCategoryDto
    {
        return CreateCategoryDto::fromArray($this->validated());
    }
}
