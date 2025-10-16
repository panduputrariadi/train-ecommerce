<?php

namespace App\Modules\Payment\Services;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Models\BankAccount;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentReceipt;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    use HandlePhotoUploadTrait;

    public function processPayment(Order $order,CreatePaymentDto $dto): Payment
    {
        return match ((int)$dto->paymentMethodId) {
            1 => $this->handleCashPayment($order, $dto),
            2 => $this->handleTransferPayment($order, $dto),
            default => throw ValidationException::withMessages([
                'payment_method_id' => 'Payment method not found',
            ]),
        };

    }

    protected function handleCashPayment(Order $order, CreatePaymentDto $dto): Payment
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

    protected function handleTransferPayment(Order $order, CreatePaymentDto $dto): Payment
    {
        $bankAccount = BankAccount::findOrFail($dto->bankAccountId);
        if (!$bankAccount) {
            throw ValidationException::withMessages([
                'bank_account_id' => 'Bank account not found',
            ]);
        }
        $grandTotal = $order->grand_total;
        $dto->paidAmount = $grandTotal;
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $dto->paymentMethodId,
            'bank_account_id' => $dto->bankAccountId,
            'amount' => $grandTotal,
            'status' => PaymentStatus::PENDING,
            'note' => $dto->notes,
            'created_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        if ($dto->evidenceFile) {
            $photoPath = $this->uploadPhoto(
                photo: $dto->evidenceFile,
                directory: 'payments',
                entityId: $payment->id,
                nameForSlug: 'payment-evidence'
            );

            PaymentReceipt::create([
                'payment_id' => $payment->id,
                'file_path' => $photoPath,
                'mime_type' => $dto->evidenceFile->getMimeType(),
                'uploaded_at' => now(),
            ]);
        }

        $order->update(['status' => OrderStatus::PROCESSING]);

        return $payment;
    }
}
