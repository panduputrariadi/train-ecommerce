<?php

namespace App\Modules\Payment\Enum;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
}
