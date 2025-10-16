<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\DTOs\Create\CreateDiscountDto;
use App\Modules\Product\Models\Discount;

class CreateDiscountAction
{
    /**
     * Create a new discount
     */
    public function execute(CreateDiscountDto $dto): Discount
    {

        $discount = Discount::create([
            'type' => $dto->type,
            'value' => $dto->value,
            'expired_at' => $dto->expired_at,
        ]);

        return $discount;
    }
}
