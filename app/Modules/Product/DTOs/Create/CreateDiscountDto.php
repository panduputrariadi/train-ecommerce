<?php

namespace App\Modules\Product\DTOs\Create;

use App\Base\BaseDto;
use DateTimeInterface;

class CreateDiscountDto extends BaseDto
{
    public string $type;

    public string $code;

    public float $value;

    public ?DateTimeInterface $expired_at = null;
}
