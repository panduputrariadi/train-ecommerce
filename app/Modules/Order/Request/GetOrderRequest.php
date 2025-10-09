<?php

namespace App\Modules\Order\Request;

use App\Modules\Order\DTOs\GetOrderDto;
use Illuminate\Foundation\Http\FormRequest;

class GetOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'max:100']
        ];
    }

    public function validatedDto(): GetOrderDto
    {
        return GetOrderDto::fromArray($this->validated());
    }
}
