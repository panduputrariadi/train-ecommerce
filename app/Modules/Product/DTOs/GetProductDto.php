<?php

namespace App\Modules\Product\DTOs;

use App\Base\BaseDto;

class GetProductDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;
}
