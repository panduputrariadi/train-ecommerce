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
        $found = Product::where('code', $product)->first();

        if (! $found) {
            throw new ModelNotFoundException('Product not found or invalid code');
        }

        return $found->load(['category', 'discounts', 'created_by']);
    }
}
