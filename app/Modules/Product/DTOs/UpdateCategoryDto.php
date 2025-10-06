<?php

namespace App\Modules\Product\DTOs;

use App\Base\BaseDto;

class UpdateCategoryDto extends BaseDto
{
    public ?string $name;

    public string $description;
}
