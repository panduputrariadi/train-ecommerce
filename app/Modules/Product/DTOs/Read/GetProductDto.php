<?php

namespace App\Modules\Product\DTOs\Read;

use App\Base\BaseDto;

class GetProductDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;
}
