<?php

namespace App\Modules\Order\SnapShots;

use App\Base\BaseSnapshot;

class ProductSnapShot extends BaseSnapshot
{
    public int $id;
    public string $code;
    public string $name;
    public float $price;
    public float $finalProce;
    public ?array $activeDiscount = null;
    public ?string $photo = null;
}
