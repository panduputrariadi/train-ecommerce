<?php

namespace App\Modules\Product\Action\Delete;

use App\Modules\Product\Models\Product;

class DeleteProductAction
{
    /**
     * Delete a product
     */
    public function execute(Product $product): bool
    {
        return (bool) $product->delete();
    }
}
