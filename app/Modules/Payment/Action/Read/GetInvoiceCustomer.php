<?php

namespace App\Modules\Payment\Action\Read;

use App\Modules\Order\Models\Order;

class GetInvoiceCustomer
{
    /**
     * Execute and return an order.
     */
    public function execute(Order $order): Order
    {
        return $order->loadMissing([
            'user.profile',
            'details.product',
            'payment.method',
        ]);
    }
}
