<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\DTOs\Read\GetCategoryDto;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Request\Read\GetCategoryRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetCategoryProductAction
{
    /**
     * Execute the GetCategoryProductAction
     *
     * @param  GetCategoryRequest  $request
     * @return LengthAwarePaginator<int, Category>
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(GetCategoryDto $dto): LengthAwarePaginator
    {
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
