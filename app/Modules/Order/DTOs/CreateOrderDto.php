<?php

namespace App\Modules\Order\DTOs;

use App\Base\BaseDto;

class CreateOrderDto extends BaseDto
{
    public array $items;

    public ?string $note;
}
