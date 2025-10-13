<?php

namespace App\Modules\Payment\Action\Read;

use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetInvoiceCustomer
{
    /**
     * Execute and return an order.
     *
     * @param Order $order
     * @return Order
     * @throws ModelNotFoundException
     */
    public function execute(Order $order): Order
    {
        if (! $order) {
            throw new ModelNotFoundException('Order not found or invalid order');
        }

        return $order->load([
            'user.profile',
            'details.product',
            'payment.method',
        ]);
    }
}
