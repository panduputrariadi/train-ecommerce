<?php

namespace App\Modules\Payment\Enum;

enum PaymentMethod: int
{
    case CASH = 1;
    case TRANSFER = 2;

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::TRANSFER => 'Bank Transfer',
        };
    }
}
