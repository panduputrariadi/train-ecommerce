<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Collections\GetCategoryProductCollection;
use App\Http\Admin\Resources\Create\CreateCategoryResource;
use App\Http\Admin\Resources\Detail\GetCategoryDetailResource;
use App\Http\Admin\Resources\Update\UpdateCategoryResource;
use App\Http\Controllers\Controller;
use App\Modules\Product\Action\Create\CreateCategoryAction;
use App\Modules\Product\Action\Detail\GetCategoryDetailAction;
use App\Modules\Product\Action\Read\GetCategoryProductAction;
use App\Modules\Product\Action\Update\UpdateCategoryAction;
use App\Modules\Product\Request\Create\CreateCategoryRequest;
use App\Modules\Product\Request\Read\GetCategoryRequest;
use App\Modules\Product\Request\Update\UpdateCategoryRequest;
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
