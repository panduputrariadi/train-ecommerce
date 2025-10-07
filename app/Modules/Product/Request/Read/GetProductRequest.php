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
            'per_page' => ['nullable', 'string'],
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
