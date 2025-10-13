<?php

namespace App\Modules\Payment\Action\Detail;

use App\Modules\Order\Models\Order;

class GetDetailOrderPaymentDetailAction
{
    /**
     * @param  string  $orderCode
     * @return \App\Modules\Payment\Models\Payment|null
     *
     */
    public function execute(Order $code)
    {

        return $code->load(['payment.receipts', 'payment.method', 'payment.bankAccount']);
    }
}
