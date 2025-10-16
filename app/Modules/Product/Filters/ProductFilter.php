<?php

namespace App\Modules\Product\Filters;

use App\Modules\Product\DTOs\Read\GetProductDto;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{
    /**
     * Construct a new ProductFilter instance.
     *
     * @param  Builder  $query  The product query builder.
     * @param  GetProductDto  $dto  The get product DTO.
     */
    public function __construct(
        protected Builder $query,
        protected GetProductDto $dto
    ) {}

    /**
     * Apply filters to a product query.
     *
     *
     * @note This function will return a Builder instance of Product model,
     * filtered by the given GetProductDto.
     */
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
