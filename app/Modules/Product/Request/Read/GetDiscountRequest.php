<?php

namespace App\Modules\Product\Request\Read;

use App\Modules\Product\DTOs\Read\GetDiscountDto;
use Illuminate\Foundation\Http\FormRequest;

class GetDiscountRequest extends FormRequest
{
    /**
     * Return validation rules for the GetCategoryRequest
     *
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer'],
        ];
    }

    public function validatedDto(): GetDiscountDto
    {
        return GetDiscountDto::fromArray($this->validated());
    }
}
