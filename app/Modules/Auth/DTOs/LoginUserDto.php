<?php

namespace App\Modules\Auth\DTOs;

use App\Base\BaseDto;

class LoginUserDto extends BaseDto
{
    public string $email;

    public string $password;
}
