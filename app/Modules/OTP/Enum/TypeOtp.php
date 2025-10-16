<?php

namespace App\Modules\OTP\Enum;

enum TypeOtp: string
{
    case TYPE_EMAIL = 'email';
    case TYPE_WHATSAPP = 'whatsapp';

    public function label(): string
    {
        return match ($this) {
            self::TYPE_EMAIL => 'email',
            self::TYPE_WHATSAPP => 'whatsapp',
        };
    }
}
