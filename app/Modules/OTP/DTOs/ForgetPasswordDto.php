<?php

namespace App\Modules\OTP\DTOs;

use App\Base\BaseDto;

class ForgetPasswordDto extends BaseDto
{
    public string $email;
}
