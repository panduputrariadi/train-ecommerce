<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Collections\GetProductCollection;
use App\Http\Admin\Resources\Create\CreateProductResource;
use App\Http\Admin\Resources\Detail\GetDetailProductResource;
use App\Http\Admin\Resources\Update\UpdateProductResource;
use App\Http\Controllers\Controller;
use App\Modules\Product\Action\Create\CreateProductAction;
use App\Modules\Product\Action\Delete\DeleteProductAction;
use App\Modules\Product\Action\Detail\GetDetailProductAction;
use App\Modules\Product\Action\Read\GetProductAction;
use App\Modules\Product\Action\Update\UpdateProductAction;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Request\Create\CreateProductRequest;
use App\Modules\Product\Request\Read\GetProductRequest;
use App\Modules\Product\Request\Update\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function createProduct(CreateProductRequest $request, CreateProductAction $action): CreateProductResource
    {
        $dto = $request->validatedDto();
        $product = DB::transaction(fn () => $action->execute($dto));

        return new CreateProductResource($product);
    }

    public function getProduct(GetProductRequest $request, GetProductAction $action): GetProductCollection
    {
        $dto = $request->validatedDto();
        $data = $action->execute($dto);

        return new GetProductCollection($data);
    }

    public function getDetailProduct(Product $product, GetDetailProductAction $action): GetDetailProductResource
    {
        $data = $action->execute($product);

        return new GetDetailProductResource($data);
    }

    public function updateProduct(Product $product, UpdateProductRequest $request, UpdateProductAction $action): UpdateProductResource
    {
        $dto = $request->validatedDto();

        $updated = DB::transaction(fn () => $action->execute($product, $dto));

        return new UpdateProductResource($updated);
    }

    public function deleteProduct(Product $product, DeleteProductAction $action): JsonResponse
    {
        DB::transaction(fn () => $action->execute($product));

        return response()->json([
            'message' => 'success deleted product',
        ]);
    }
}
