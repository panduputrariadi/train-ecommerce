<?php

namespace App\Modules\Payment\Action\Create;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Services\PaymentService;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;

class CreatePaymentAction
{
    use HandlePhotoUploadTrait;

    public function __construct(protected PaymentService $service) {}

    public function execute(Order $order, CreatePaymentDto $dto): Payment
    {
        return $this->service->processPayment($order, $dto);
    }
}
