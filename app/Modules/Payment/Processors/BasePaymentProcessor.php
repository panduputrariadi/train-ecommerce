<?php

namespace App\Modules\Payment\Processors;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Models\Payment;

abstract class BasePaymentProcessor
{
    abstract public function handle(Order $order, CreatePaymentDto $dto): Payment;
}
