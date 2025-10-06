<?php

namespace App\Modules\Product\Action;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetDetailProductAction
{
    public function execute(string $code): Product
    {
        $data = Product::where('code', $code)->first();
        if (! $data) {
            throw new ModelNotFoundException('Product not found');
        }

        return $data;
    }
}
