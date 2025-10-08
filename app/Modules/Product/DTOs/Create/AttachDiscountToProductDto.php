<?php

namespace App\Modules\Product\DTOs\Create;

use App\Base\BaseDto;

class AttachDiscountToProductDto extends BaseDto
{
    public int $discountId;

    /**
     * @var int[]
     */
    public array $productIds;
}
