<?php

namespace App\Modules\Payment\Action\Update;

use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Models\Payment;

class DeclinePaymentOrder
{
    /**
     * Decline a payment order
     *
     * @param Payment $code
     * @return Payment
     */
    public function execute(Payment $code): Payment
    {
        $code->update(['status' => PaymentStatus::REJECTED]);

        return $code;
    }
}
