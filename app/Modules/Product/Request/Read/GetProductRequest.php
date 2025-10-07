<?php

namespace App\Modules\Product\Request\Read;

use App\Modules\Product\DTOs\Read\GetProductDto;
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
