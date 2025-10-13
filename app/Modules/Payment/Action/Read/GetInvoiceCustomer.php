<?php

namespace App\Modules\Payment\Action\Read;

use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetInvoiceCustomer
{
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
