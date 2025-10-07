<?php

namespace App\Modules\Product\Action\Detail;

use App\Modules\Product\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetCategoryDetailAction
{
    /**
     * Execute action to get category detail
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id): Category
    {
        $data = Category::withoutTrashed()->find($id);

        if (! $data) {
            throw new ModelNotFoundException('Category not found.');
        }

        return $data;
    }
}
