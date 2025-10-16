<?php

namespace App\Modules\Customer\Request;

use App\Modules\Customer\DTOs\GetListCustomerDto;
use Illuminate\Foundation\Http\FormRequest;

class GetListCustomer extends FormRequest
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
        ];
    }

    /**
     * Return a validated GetListCustomerDto
     */
    public function validatedDto(): GetListCustomerDto
    {
        return GetListCustomerDto::fromArray($this->validated());
    }
}
