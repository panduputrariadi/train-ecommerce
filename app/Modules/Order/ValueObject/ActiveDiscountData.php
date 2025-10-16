<?php

namespace App\Modules\Order\ValueObject;

use App\Base\BaseValueObject;

class ActiveDiscountData extends BaseValueObject
{
    public ?int $id = null;

    public ?string $code = null;

    public ?string $type = null;

    public ?float $value = null;

    public ?string $expired_at = null;
}
