<?php

namespace App\Modules\Customer\DTOs;

use App\Base\BaseDto;

class GetListCustomerDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;
}
