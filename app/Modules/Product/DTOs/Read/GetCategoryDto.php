<?php

namespace App\Modules\Product\DTOs\Read;

use App\Base\BaseDto;

class GetCategoryDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;
}
