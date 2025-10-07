<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\AttachDiscountToProductDto;
use App\Modules\Product\Models\DiscountProduct;
use Illuminate\Support\Facades\Auth;

class AttachDiscountToProductsAction
{
    public function execute(AttachDiscountToProductDto $dto): DiscountProduct
    {
        foreach ($dto->productIds as $productId) {
            $data = DiscountProduct::query()->create([
                'product_id' => $productId,
                'discount_id' => $dto->discountId,
                'created_by' => Auth::id(),
            ]);
            $data->product()->where('id', $productId)->update(['is_discount' => true]);
        }

        return $data;
    }
}
