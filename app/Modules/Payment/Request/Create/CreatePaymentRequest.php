<?php

namespace App\Modules\Payment\Request\Create;

use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'paid_amount' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string'],
            'evidence_file' => ['nullable', 'file', 'mimes:png,jpg,pdf,jpeg', 'max:2048'],
        ];
    }

    public function validatedDto(): CreatePaymentDto
    {
        return CreatePaymentDto::fromArray($this->validated());
    }
}
