<?php

namespace App\Modules\Product\Action\Detail;

use App\Modules\Product\Models\Product;

class GetDetailProductAction
{
    /**
     * Execute action to get product detail
     */
    public function execute(Product $product): Product
    {
        return $product->load(['category:id,name']);
    }
}
