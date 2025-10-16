<?php

namespace App\Modules\Order\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case REFUNDED = 'refunded';
    case FAILED = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            self::COMPLETED => 'Completed',
            self::REFUNDED => 'Refunded',
            self::FAILED => 'Failed',
        };
    }
}
