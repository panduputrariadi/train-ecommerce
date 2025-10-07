<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Read\GetProductRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetProductAction
{
    /**
     * Execute the GetProductAction
     *
     * @param GetProductRequest $request
     * @return LengthAwarePaginator<int, Product>
     */
    public function execute(GetProductRequest $request): LengthAwarePaginator
    {
        $dto = $request->validatedDto();

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
