<?php

namespace App\Modules\Order\ValueObject;

use App\Base\BaseValueObject;

class ProductData extends BaseValueObject
{
    public ?int $id = 0;

    public string $code;

    public string $name;

    public ?string $photo = null;

    public float $price;

    public float $finalPrice;

    public ?ActiveDiscountData $activeDiscount = null;
}
