<?php

namespace App\Modules\Order\ValueObject;

use App\Base\BaseValueObject;

class ProductData extends BaseValueObject
{
    public int $id;
    public string $code;
    public string $name;
    public ?string $photo = null;
    public float $price;
    public float $final_price;
    public ?ActiveDiscountData $active_discount = null;
}
