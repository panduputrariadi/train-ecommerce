<?php

namespace App\Modules\OTP\DTOs;

use App\Base\BaseDto;

class ResetPasswordDto extends BaseDto
{
    public int $otpId;

    public string $password;
}
