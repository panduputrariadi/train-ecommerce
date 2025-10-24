<?php

namespace App\Modules\Payment\Processors;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CashPaymentProcessor extends BasePaymentProcessor
{

    /**
     * Handle cash payment
     *
     * @param Order $order
     * @param CreatePaymentDto $dto
     * @return Payment
     * @throws ValidationException
     */
    public function handle(Order $order, CreatePaymentDto $dto): Payment
    {
        $this->validatePayment($order, $dto);

        $payment = $this->createPayment($order, $dto);

        $this->updateOrderStatus($order);

        return $payment;
    }

    /**
     * Validate the payment information.
     *
     * @throws ValidationException
     */
    protected function validatePayment(Order $order, CreatePaymentDto $dto): void
    {
        if ($dto->paidAmount < $order->grand_total) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Paid amount is less than grand total',
            ]);
        }
    }

    /**
     * Create a payment for the given order and payment information.
     *
     * @param  Order $order
     * @param  CreatePaymentDto $dto
     * @return Payment
     */
    protected function createPayment(Order $order, CreatePaymentDto $dto): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $dto->paymentMethodId,
            'amount' => $dto->paidAmount,
            'status' => PaymentStatus::COMPLETED,
            'note' => $dto->notes,
            'created_by' => Auth::id(),
            'paid_at' => now(),
        ]);
    }

    /**
     * Update the status of the order to COMPLETED.
     *
     * @param Order $order
     */
    protected function updateOrderStatus(Order $order): void
    {
        $order->update(['status' => OrderStatus::COMPLETED]);
    }
}
