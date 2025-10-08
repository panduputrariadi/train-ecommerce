<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Collections\GetDiscountCollection;
use App\Http\Admin\Resources\Create\AttachDiscountToProductsResource;
use App\Http\Admin\Resources\Create\CreateDiscountResource;
use App\Http\Controllers\Controller;
use App\Modules\Product\Action\Create\AttachDiscountToProductsAction;
use App\Modules\Product\Action\Create\CreateDiscountAction;
use App\Modules\Product\Action\Read\GetDiscountAction;
use App\Modules\Product\Request\Create\AttachDiscountToProductRequest;
use App\Modules\Product\Request\Create\CreateDiscountRequest;
use App\Modules\Product\Request\Read\GetDiscountRequest;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function createDiscount(CreateDiscountRequest $request, CreateDiscountAction $action): CreateDiscountResource
    {
        $dto = $request->validated();
        $discount = DB::transaction(fn () => $action->execute($dto));

        return new CreateDiscountResource($discount);
    }

    public function getDiscount(GetDiscountRequest $request, GetDiscountAction $action): GetDiscountCollection
    {
        $dto = $request->validated();
        $data = $action->execute($dto);

        return new GetDiscountCollection($data);
    }

    public function attachDiscountToProducts(AttachDiscountToProductRequest $request, AttachDiscountToProductsAction $action): AttachDiscountToProductsResource
    {
        $dto = $request->validated();
        $attachDiscount = DB::transaction(fn () => $action->execute($dto));

        return new AttachDiscountToProductsResource($attachDiscount);
    }
}
