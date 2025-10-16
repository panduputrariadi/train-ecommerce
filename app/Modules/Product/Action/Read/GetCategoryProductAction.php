<?php

namespace App\Modules\Product\Action\Read;

use App\Modules\Product\DTOs\Read\GetCategoryDto;
use App\Modules\Product\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetCategoryProductAction
{
    /**
     * Execute the GetCategoryProductAction
     *
     * @return LengthAwarePaginator<int, Category>
     *
     * @note This function will return a LengthAwarePaginator of Category models.
     * If the search parameter is filled, it will query the categories with
     * name or slug like the search parameter.
     * If the search parameter is empty, it will return all categories.
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
