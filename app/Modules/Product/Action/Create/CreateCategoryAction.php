<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\CreateCategoryDto;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Request\Create\CreateCategoryRequest;
use Illuminate\Support\Str;

class CreateCategoryAction
{
    /**
     * Execute the creation of a new category
     *
     * @param  CreateCategoryRequest  $request
     */
    public function execute(CreateCategoryDto $dto): Category
    {
        $category = Category::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'slug' => Str::slug($dto->name),
        ]);

        return $category;
    }
}
