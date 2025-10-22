<?php

namespace App\Modules\Order\SnapShots;

use App\Base\BaseSnapshot;

class ProductSnapShot extends BaseSnapshot
{
    public int $id;
    public string $code;
    public string $name;
    public float $price;
    public float $final_price;
    public ?array $active_discount = null;
    public ?string $photo = null;
}
