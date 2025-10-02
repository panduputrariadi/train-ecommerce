<?php

namespace App\Modules\OTP\DTOs;

use App\Base\BaseDto;

class SendOtpDto extends BaseDto
{
    public string $email;
}
