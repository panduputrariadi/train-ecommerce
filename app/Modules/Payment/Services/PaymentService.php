<?php

namespace App\Modules\Payment\Services;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Enum\PaymentMethodEnum;
use App\Modules\Payment\Factories\PaymentFactory;
use App\Modules\Payment\Models\Payment;

class PaymentService
{
    public function processPayment(Order $order, CreatePaymentDto $dto): Payment
    {
        $method = PaymentMethodEnum::from($dto->paymentMethodId);
        $processor = PaymentFactory::make($method);

        return $processor->handle($order, $dto);
    }
}
