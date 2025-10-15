<?php

namespace App\Modules\Payment\Action\Create;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentReceipt;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;
use Illuminate\Support\Facades\Auth;

class CreatePaymentAction
{
    use HandlePhotoUploadTrait;

    /**
     * Execute the creation of a new payment
     *
     * @param CreatePaymentDto $dto
     * @return Payment
     */
    public function execute(CreatePaymentDto $dto): Payment
    {
        $order = Order::findOrFail($dto->orderId);

        $payment = Payment::create([
            'order_id' => $dto->orderId,
            'payment_method_id' => $dto->paymentMethodId,
            'bank_account_id' => $dto->bankAccountId ?? null,
            'amount' => $dto->paidAmount,
            'status' => PaymentStatus::PENDING,
            'note' => $dto->notes ?? null,
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
