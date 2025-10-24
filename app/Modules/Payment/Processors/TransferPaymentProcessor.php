<?php

namespace App\Modules\Payment\Processors;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Models\BankAccount;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentReceipt;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;
use Illuminate\Support\Facades\Auth;

class TransferPaymentProcessor extends BasePaymentProcessor
{
    use HandlePhotoUploadTrait;

    /**
     * Handle transfer payment
     *
     * @param Order $order
     * @param CreatePaymentDto $dto
     * @return Payment
     */
    public function handle(Order $order, CreatePaymentDto $dto): Payment
    {
        $bankAccount = $this->validateBankAccount($dto);
        $dto->paidAmount = $order->grand_total;

        $payment = $this->createPayment($order, $dto, $bankAccount);

        if ($dto->evidenceFile) {
            $this->attachPaymentReceipt($payment, $dto);
        }

        $this->updateOrderStatus($order);

        return $payment;
    }

    /**
     * Validate the bank account of the given payment information.
     *
     * @param CreatePaymentDto $dto
     * @return BankAccount
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function validateBankAccount(CreatePaymentDto $dto): BankAccount
    {
        return BankAccount::findOrFail($dto->bankAccountId);
    }

    /**
     * Create a payment for the given order and payment information.
     *
     * @param Order $order
     * @param CreatePaymentDto $dto
     * @param BankAccount $bankAccount
     * @return Payment
     */
    protected function createPayment(Order $order, CreatePaymentDto $dto, BankAccount $bankAccount): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $dto->paymentMethodId,
            'bank_account_id' => $bankAccount->id,
            'amount' => $dto->paidAmount,
            'status' => PaymentStatus::PENDING,
            'note' => $dto->notes,
            'created_by' => Auth::id(),
            'paid_at' => now(),
        ]);
    }

    /**
     * Attach payment receipt to the given payment.
     *
     * @param Payment $payment
     * @param CreatePaymentDto $dto
     */
    protected function attachPaymentReceipt(Payment $payment, CreatePaymentDto $dto): void
    {
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

    /**
     * Update the status of the order to PROCESSING.
     *
     * @param Order $order
     */
    protected function updateOrderStatus(Order $order): void
    {
        $order->update(['status' => OrderStatus::PROCESSING]);
    }
}
