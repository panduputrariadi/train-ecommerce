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
        if ($dto->paidAmount < $order->grand_total) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Paid amount is less than grand total',
            ]);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $dto->paymentMethodId,
            'amount' => $dto->paidAmount,
            'status' => PaymentStatus::COMPLETED,
            'note' => $dto->notes,
            'created_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        $order->update(['status' => OrderStatus::COMPLETED]);

        return $payment;
    }
}
