<?php

namespace App\Modules\Product\Action;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Request\CreateCategoryRequest;
use Illuminate\Support\Str;

class CreateCategoryAction
{
    public function execute(CreateCategoryRequest $request): Category
    {
        $dto = $request->validatedDto();
        $category = Category::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'slug' => Str::slug($dto->name),
        ]);

        return $category;
    }
}
