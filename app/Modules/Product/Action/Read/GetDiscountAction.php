<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\DTOs\Read\GetDiscountDto;
use App\Modules\Product\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetDiscountAction
{
    /**
     * Execute the GetDiscountAction
     *
     * @param GetDiscountDto $dto
     * @return LengthAwarePaginator<int, Discount>
     *
     * @note This function will return a LengthAwarePaginator of Discount models.
     * If the search parameter is filled, it will query the discounts with
     * code or value like the search parameter.
     * If the search parameter is empty, it will return all discounts.
     */
    public function execute(GetDiscountDto $dto): LengthAwarePaginator
    {
        $search = $dto->search ?? '';
        $perPage = $dto->perPage ?? 10;

        $query = Discount::with(['products.discounts'])->latest();
        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
