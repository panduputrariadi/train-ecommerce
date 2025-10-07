<?php

namespace App\Modules\Product\Request\Create;

use App\Modules\Product\DTOs\Create\CreateDiscountDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateDiscountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type'       => ['required', 'string', 'in:percentage,nominal'],
            'code'       => ['required', 'string', 'unique:discounts,code'],
            'value'      => ['required', 'numeric', 'min:0'],
            'expired_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function validatedDto(): CreateDiscountDto
    {
        return CreateDiscountDto::fromArray($this->validated());
    }
}
