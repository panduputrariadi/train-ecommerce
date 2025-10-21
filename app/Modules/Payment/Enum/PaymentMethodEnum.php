<?php

namespace App\Modules\Payment\Enum;

enum PaymentMethodEnum: string
{
    case CASH = 'Cash';
    case TRANSFER = 'Bank Transfer';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::TRANSFER => 'Bank Transfer',
        };
    }
}
