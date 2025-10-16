<?php

namespace App\Modules\Order\DTOs;

use App\Base\BaseDto;

class GetOrderDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;
}
