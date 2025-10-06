<?php

namespace App\Modules\Product\Action;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Request\GetCategoryRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetCategoryProductAction
{
    public function execute(GetCategoryRequest $request): LengthAwarePaginator
    {
        $dto = $request->validatedDto();

        $search = $dto->search ?? '';
        $perPage = $dto->perPage ?? 10;

        $query = Category::withoutTrashed()->latest();

        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
