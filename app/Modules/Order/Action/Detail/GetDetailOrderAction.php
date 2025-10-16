<?php

namespace App\Modules\Order\Action\Detail;

use App\Modules\Order\Models\Order;

class GetDetailOrderAction
{
    public function execute(Order $order): Order
    {
        return $order->load(['details']);
    }
}
