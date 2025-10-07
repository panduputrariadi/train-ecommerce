<?php

namespace App\Modules\Product\Action\Update;

use App\Modules\Product\DTOs\Update\UpdateCategoryDto;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Request\Update\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateCategoryAction
{

    /**
     * Execute the update of a category
     *
     * @param  UpdateCategoryDto  $dto
     * @param int $id
     * @return Category
     * @throws ModelNotFoundException
     */
    public function execute(UpdateCategoryDto $dto, int $id): Category
    {
        $data = Category::withoutTrashed()->find($id);
        if (! $data) {
            throw new ModelNotFoundException('Category not found.');
        }

        $data->update([
            'name' => $dto->name ?? $data->name,
            'description' => $dto->description ?? $data->description,
        ]);

        return $data;
    }
}
