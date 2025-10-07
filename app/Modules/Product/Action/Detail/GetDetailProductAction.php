<?php

namespace App\Modules\Product\Action\Detail;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetDetailProductAction
{

    /**
     * Execute action to get product detail
     *
     * @param string $code
     * @return Product
     * @throws ModelNotFoundException
     */
    public function execute(string $code): Product
    {
        $data = Product::where('code', $code)->first();
        if (! $data) {
            throw new ModelNotFoundException('Product not found');
        }

        return $data;
    }
}
