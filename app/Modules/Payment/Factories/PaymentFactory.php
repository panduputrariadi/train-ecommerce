<?php

namespace App\Modules\Payment\Factories;

use App\Modules\Payment\Enum\PaymentMethodEnum;
use App\Modules\Payment\Processors\BasePaymentProcessor;
use App\Modules\Payment\Processors\CashPaymentProcessor;
use App\Modules\Payment\Processors\TransferPaymentProcessor;
use Illuminate\Validation\ValidationException;

class PaymentFactory
{
    /**
     * Make a payment processor based on the given payment method.
     *
     * @param PaymentMethodEnum $method
     * @return BasePaymentProcessor
     *
     * @throws ValidationException
     */
    public static function make(PaymentMethodEnum $method): BasePaymentProcessor
    {
        return match ($method) {
            PaymentMethodEnum::CASH => new CashPaymentProcessor(),
            PaymentMethodEnum::TRANSFER => new TransferPaymentProcessor(),
            default => throw ValidationException::withMessages([
                'payment_method_id' => 'Payment method not supported',
            ]),
        };
    }
}
