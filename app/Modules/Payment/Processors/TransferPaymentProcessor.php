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
        $bankAccount = BankAccount::findOrFail($dto->bankAccountId);

        $dto->paidAmount = $order->grand_total;

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $dto->paymentMethodId,
            'bank_account_id' => $dto->bankAccountId,
            'amount' => $dto->paidAmount,
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
