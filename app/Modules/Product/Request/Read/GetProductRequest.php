<?php

namespace App\Modules\Product\Request\Read;

use App\Modules\Product\DTOs\Read\GetProductDto;
use Illuminate\Foundation\Http\FormRequest;

class GetProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'int', 'max:100'],
            'category_id' => ['nullable', 'int'],
            'created_by' => ['nullable', 'int'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric'],
            'has_discount' => ['nullable', 'boolean'],
            'created_from' => ['nullable', 'date'],
            'created_to' => ['nullable', 'date'],
            'in_stock' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Return a validated GetProductDto
     */
    public function validatedDto(): GetProductDto
    {
        return GetProductDto::fromArray($this->validated());
    }
}
