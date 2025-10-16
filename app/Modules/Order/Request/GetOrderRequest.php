<?php

namespace App\Modules\Order\Request;

use App\Modules\Order\DTOs\GetOrderDto;
use Illuminate\Foundation\Http\FormRequest;

class GetOrderRequest extends FormRequest
{
    /**
     * Return validation rules for the GetOrderRequest
     *
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'max:100'],
        ];
    }

    /**
     * Return a validated GetOrderDto
     */
    public function validatedDto(): GetOrderDto
    {
        return GetOrderDto::fromArray($this->validated());
    }
}
