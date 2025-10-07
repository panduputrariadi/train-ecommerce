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
use App\Modules\Product\Request\Create\CreateProductRequest;
use App\Modules\Product\Request\Read\GetProductRequest;
use App\Modules\Product\Request\Update\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function createProduct(CreateProductRequest $request, CreateProductAction $action): CreateProductResource
    {
        $product = DB::transaction(fn () => $action->execute($request));

        return new CreateProductResource($product);
    }

    public function getProduct(GetProductRequest $request, GetProductAction $action): GetProductCollection
    {
        $data = $action->execute($request);

        return new GetProductCollection($data);
    }

    public function updateProduct(string $code, UpdateProductRequest $request, UpdateProductAction $action): UpdateProductResource
    {
        $product = DB::transaction(fn () => $action->execute($code, $request));

        return new UpdateProductResource($product);
    }

    public function getDetailProduct(string $code, GetDetailProductAction $action): GetDetailProductResource
    {
        $product = $action->execute($code);

        return new GetDetailProductResource($product);
    }

    public function deleteProduct(string $code, DeleteProductAction $action): JsonResponse
    {
        $deleted = $action->execute($code);

        if (! $deleted) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'message' => 'success deleted product'
        ]);
    }
}
