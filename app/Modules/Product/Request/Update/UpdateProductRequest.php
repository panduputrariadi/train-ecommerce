<?php

namespace App\Modules\Product\Request\Update;

use App\Modules\Product\DTOs\Update\UpdateProductDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{

    /**
     * Validation rules for update product request.
     *
     * @return array<string, array<int, string>|string>
     */
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


    /**
     * Return a validated UpdateProductDto
     *
     * @return UpdateProductDto
     */
    public function validatedDto(): UpdateProductDto
    {
        return UpdateProductDto::fromArray($this->validated());
    }
}
