<?php

namespace App\Modules\Product\Request;

use App\Modules\Product\DTOs\CreateProductDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'is_discount' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function validatedDto(): CreateProductDto
    {
        return CreateProductDto::fromArray($this->validated());
    }
}
