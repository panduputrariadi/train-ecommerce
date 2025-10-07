<?php

namespace App\Modules\Product\Request\Create;

use App\Modules\Product\DTOs\Create\CreateCategoryDto;
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
