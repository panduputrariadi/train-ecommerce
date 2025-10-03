<?php

namespace App\Modules\OTP\DTOs;

use App\Base\BaseDto;

class VerifyOtpDto extends BaseDto
{
    public string $otp;

    public int $id;
}
