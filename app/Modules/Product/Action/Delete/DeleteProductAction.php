<?php

namespace App\Modules\Product\Action\Delete;

use App\Modules\Product\Models\Product;

class DeleteProductAction
{
    public function execute(string $code): bool
    {
        $product = Product::where('code', $code)->first();
        return $product->delete();
    }
}
