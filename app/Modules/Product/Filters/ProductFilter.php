<?php

namespace App\Modules\Product\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\Product\DTOs\Read\GetProductDto;
use App\Modules\Product\Models\Product;

class ProductFilter
{
    public function __construct(
        protected Builder $query,
        protected GetProductDto $dto
    ) {}

    public static function apply(GetProductDto $dto): Builder
    {
        return Product::with(['category:id,name', 'discounts'])
            ->search($dto->search)
            ->ofCategory($dto->categoryId)
            ->discounted($dto->hasDiscount)
            ->priceBetween($dto->minPrice, $dto->maxPrice)
            ->inStock($dto->inStock)
            ->createdBetween($dto->createdFrom, $dto->createdTo);
    }
}
