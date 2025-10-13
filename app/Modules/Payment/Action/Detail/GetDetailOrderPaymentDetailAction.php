<?php

namespace App\Modules\Payment\Action\Detail;

use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetDetailOrderPaymentDetailAction
{
    /**
     * @param  string  $orderCode
     * @return \App\Modules\Payment\Models\Payment|null
     *
     * @throws ModelNotFoundException
     */
    public function execute(Order $code)
    {
        if (! $code) {
            throw new ModelNotFoundException('Order not found or invalid code');
        }

        return $code->load(['payment.receipts', 'payment.method', 'payment.bankAccount']);
    }
}
