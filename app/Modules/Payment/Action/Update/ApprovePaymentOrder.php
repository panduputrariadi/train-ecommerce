<?php

namespace App\Modules\Payment\Action\Update;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Models\Payment;

class ApprovePaymentOrder
{
    /**
     * Update payment status to COMPLETED and update order status to COMPLETED.
     */
    public function execute(Payment $code): Payment
    {
        $code->update(['status' => PaymentStatus::COMPLETED]);
        $code->order->update(['status' => OrderStatus::COMPLETED]);

        event(new PaymentCompleted($code));

        return $code;
    }
}
