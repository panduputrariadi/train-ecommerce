<?php

namespace App\Modules\Product\DTOs\Read;

use App\Base\BaseDto;

class GetDiscountDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;
}
