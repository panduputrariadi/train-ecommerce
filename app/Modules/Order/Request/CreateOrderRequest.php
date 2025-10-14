<?php

namespace App\Modules\Order\Request;

use App\Modules\Order\DTOs\CreateOrderDto;
use App\Modules\Order\Rule\ExistsAllProducts;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'note' => 'nullable|string',
            'items' => ['required', 'array', 'min:1', new ExistsAllProducts],
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Convert validated input to DTO.
     */
    public function validatedDto(): CreateOrderDto
    {
        return CreateOrderDto::fromArray($this->validated());
    }
}
