<?php

namespace App\Modules\Product\Action;

use App\Modules\Product\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetCategoryDetailAction
{
    public function execute(int $id): Category
    {
        $data = Category::withoutTrashed()->find($id);

        if (! $data) {
            throw new ModelNotFoundException('Category not found.');
        }

        return $data;
    }
}
