<?php

namespace App\Modules\Order\Action\Detail;

use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetDetailOrderAction
{
    public function execute(Order $order): Order
    {
        if (!$order) {
            throw new ModelNotFoundException('Order not found or invalid code');
        }

        return $order->load(['details']);
    }
}
