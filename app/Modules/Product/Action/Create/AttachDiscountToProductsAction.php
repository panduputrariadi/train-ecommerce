<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\AttachDiscountToProductDto;
use App\Modules\Product\Models\DiscountProduct;
use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttachDiscountToProductsAction
{
    /**
     * Attach discount to products
     *
     * @param  AttachDiscountToProductDto  $dto
     * @return  DiscountProduct
     */
    public function execute(AttachDiscountToProductDto $dto): void
    {
        $productIds = $dto->productIds;
        $discountId = $dto->discountId;
        $userId = Auth::id();

        if (empty($productIds)) {
            return;
        }

        $productIds = array_unique($productIds);

        $now = now();
        $discountProductData = [];

        foreach ($productIds as $productId) {
            $discountProductData[] = [
                'product_id' => $productId,
                'discount_id' => $discountId,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('discount_products')->insert($discountProductData);

        Product::whereIn('id', $productIds)
            ->update(['is_discount' => true]);
    }
}
