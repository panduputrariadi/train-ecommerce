<?php

namespace App\Modules\OTP\Enum;

enum UseOtp: string
{
    case USED_FOR_REGISTER = 'register';
    case USED_FOR_FORGOT_PASSWORD = 'forgot_password';

    public function label(): string
    {
        return match ($this) {
            self::USED_FOR_REGISTER => 'register',
            self::USED_FOR_FORGOT_PASSWORD => 'forgot_password',
        };
    }
}
