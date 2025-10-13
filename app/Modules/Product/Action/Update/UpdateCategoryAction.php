<?php

namespace App\Modules\Product\Action\Update;

use App\Modules\Product\DTOs\Update\UpdateCategoryDto;
use App\Modules\Product\Models\Category;

class UpdateCategoryAction
{

    /**
     * Execute the update of a category
     *
     * @param  UpdateCategoryDto  $dto
     * @param int $id
     * @return Category
     */
    public function execute(UpdateCategoryDto $dto, int $id): Category
    {
        $data = Category::withoutTrashed()->find($id);

        $data->update([
            'name' => $dto->name ?? $data->name,
            'description' => $dto->description ?? $data->description,
        ]);

        return $data;
    }
}
