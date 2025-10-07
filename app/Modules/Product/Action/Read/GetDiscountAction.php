<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\DTOs\Read\GetDiscountDto;
use App\Modules\Product\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetDiscountAction
{
    public function execute(GetDiscountDto $dto): LengthAwarePaginator
    {
        $search = $dto->search ?? '';
        $perPage = $dto->perPage ?? 10;

        $query = Discount::with(['products:id,name']);
        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
