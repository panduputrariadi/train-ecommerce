<?php

namespace App\Modules\Product\Action\Detail;

use App\Modules\Product\Models\Category;

class GetCategoryDetailAction
{
    /**
     * Execute action to get category detail
     */
    public function execute(int $id): Category
    {
        $data = Category::withoutTrashed()->find($id);

        return $data;
    }
}
