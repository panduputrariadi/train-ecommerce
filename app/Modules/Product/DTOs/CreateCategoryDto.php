<?php

namespace App\Modules\Product\DTOs;

use App\Base\BaseDto;

class CreateCategoryDto extends BaseDto
{
    public string $name;

    public string $description;
}
