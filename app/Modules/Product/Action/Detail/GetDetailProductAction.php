<?php

namespace App\Modules\Product\Action\Detail;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetDetailProductAction
{

    /**
     * Execute action to get product detail
     *
     * @throws ModelNotFoundException
     * @param  Product $product
     * @return Product
     */
    public function execute(Product $product): Product
    {
        if (! $product) {
            throw new ModelNotFoundException('Product not found or invalid code');
        }

        return $product->load(['category:id,name']);
    }
}
