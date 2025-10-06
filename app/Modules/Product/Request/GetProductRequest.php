<?php

namespace App\Modules\Product\Request;

use App\Modules\Product\DTOs\GetProductDto;
use Illuminate\Foundation\Http\FormRequest;

class GetProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'string'],
        ];
    }

    public function validatedDto(): GetProductDto
    {
        return GetProductDto::fromArray($this->validated());
    }
}
