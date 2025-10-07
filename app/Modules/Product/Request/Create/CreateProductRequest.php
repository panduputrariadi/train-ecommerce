<?php

namespace App\Modules\Product\Request\Create;

use App\Modules\Product\DTOs\Create\CreateProductDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Return rules for validation
     *
     * @return array<string, array<int, string>|string>
     */
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

    /**
     * Return a validated CreateProductDto
     */
    public function validatedDto(): CreateProductDto
    {
        return CreateProductDto::fromArray($this->validated());
    }
}
