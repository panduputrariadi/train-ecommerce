<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Collections\GetCategoryProductCollection;
use App\Http\Admin\Resources\CreateCategoryResource;
use App\Http\Admin\Resources\GetCategoryDetailResource;
use App\Http\Admin\Resources\UpdateCategoryResource;
use App\Http\Controllers\Controller;
use App\Modules\Product\Action\CreateCategoryAction;
use App\Modules\Product\Action\GetCategoryDetailAction;
use App\Modules\Product\Action\GetCategoryProductAction;
use App\Modules\Product\Action\UpdateCategoryAction;
use App\Modules\Product\Request\CreateCategoryRequest;
use App\Modules\Product\Request\GetCategoryRequest;
use App\Modules\Product\Request\UpdateCategoryRequest;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function createCategory(CreateCategoryRequest $request, CreateCategoryAction $action): CreateCategoryResource
    {
        $data = DB::transaction(fn () => $action->execute($request));

        return new CreateCategoryResource($data);
    }

    public function getDataCategory(GetCategoryRequest $request, GetCategoryProductAction $action): GetCategoryProductCollection
    {
        $categories = $action->execute($request);

        return new GetCategoryProductCollection($categories);
    }

    public function getCategoryDetail(int $id, GetCategoryDetailAction $action): GetCategoryDetailResource
    {
        $category = $action->execute($id);

        return new GetCategoryDetailResource($category);
    }

    public function updateCategory(int $id, UpdateCategoryAction $action, UpdateCategoryRequest $request): UpdateCategoryResource
    {
        $category = DB::transaction(fn () => $action->execute($request, $id));

        return new UpdateCategoryResource($category);
    }
}
