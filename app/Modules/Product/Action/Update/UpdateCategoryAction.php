<?php

namespace App\Modules\Product\Action\Update;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Request\Update\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateCategoryAction
{
    public function execute(UpdateCategoryRequest $request, int $id): Category
    {
        $data = Category::withoutTrashed()->find($id);
        if (! $data) {
            throw new ModelNotFoundException('Category not found.');
        }
        $dto = $request->validatedDto();
        $data->update([
            'name' => $dto->name,
            'description' => $dto->description,
        ]);

        return $data;
    }
}
