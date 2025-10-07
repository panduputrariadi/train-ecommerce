<?php

namespace App\Modules\Product\Action\Create;

use App\Modules\Product\Models\Discount;
use App\Modules\Product\Request\Create\CreateDiscountRequest;

class CreateDiscountAction
{
    public function execute(CreateDiscountRequest $request): Discount
    {
        $dto = $request->validatedDto();

        $discount = Discount::create([
            'type'       => $dto->type,
            'code'       => $dto->code,
            'value'      => $dto->value,
            'expired_at' => $dto->expired_at,
        ]);

        return $discount;
    }
}
