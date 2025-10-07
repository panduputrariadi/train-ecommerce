<?php

namespace App\Modules\Product\Request\Create;

use App\Modules\Product\DTOs\Create\AttachDiscountToProductDto;
use Illuminate\Foundation\Http\FormRequest;

class AttachDiscountToProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Return the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'exists:products,id'],
            'discount_id' => ['required', 'exists:discounts,id'],
        ];
    }

    /**
     * Return a validated AttachDiscountToProductDto
     *
     * @return AttachDiscountToProductDto
     */
    public function validatedDto(): AttachDiscountToProductDto
    {
        return AttachDiscountToProductDto::fromArray($this->validated());
    }
}
