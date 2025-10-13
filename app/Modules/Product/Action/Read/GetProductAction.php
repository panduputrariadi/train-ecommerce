<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\DTOs\Read\GetProductDto;
use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetProductAction
{

    /**
     * Execute the GetProductAction
     *
     * @param  GetProductDto  $dto
     * @return LengthAwarePaginator<int, Product>
     *
     */
    public function execute(GetProductDto $dto): LengthAwarePaginator
    {
        $search = $dto->search ?? '';
        $perPage = $dto->perPage ?? 10;

        $query = Product::with(['category:id,name'])
            ->when(filled($search), function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        // ->latest();

        return $query->paginate($perPage);
    }
}
