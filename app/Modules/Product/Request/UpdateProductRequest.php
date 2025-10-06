<?php

namespace App\Modules\Product\Request;

use App\Modules\Product\DTOs\UpdateProductDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_discount' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function validatedDto(): UpdateProductDto
    {
        return UpdateProductDto::fromArray($this->validated());
    }
}
