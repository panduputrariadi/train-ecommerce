<?php

namespace App\Modules\Payment\Action\Read;

use App\Modules\Order\Models\Order;

class GetInvoiceCustomer
{
    /**
     * Execute and return an order.
     *
     * @param Order $order
     * @return Order
     */
    public function execute(Order $order): Order
    {
        return $order->load([
            'user.profile',
            'details.product',
            'payment.method',
        ]);
    }
}
