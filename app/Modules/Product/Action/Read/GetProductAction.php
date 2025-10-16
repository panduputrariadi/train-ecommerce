<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\DTOs\Read\GetProductDto;
use App\Modules\Product\Filters\ProductFilter;
use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetProductAction
{
    /**
     * Execute the GetProductAction
     *
     * @return LengthAwarePaginator<int, Product>
     */
    public function execute(GetProductDto $dto): LengthAwarePaginator
    {
        $query = ProductFilter::apply($dto);

        return $query->paginate($dto->perPage ?? 10);
    }
}
