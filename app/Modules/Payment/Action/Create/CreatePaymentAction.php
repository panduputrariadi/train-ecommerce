<?php

namespace App\Modules\Payment\Action\Create;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\DTOs\Create\CreatePaymentDto;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentReceipt;
use App\Modules\Payment\Services\PaymentService;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;
use Illuminate\Support\Facades\Auth;

class CreatePaymentAction
{
    use HandlePhotoUploadTrait;

    public function __construct(protected PaymentService $service){}
    public function execute(CreatePaymentDto $dto): Payment
    {
        return $this->service->processPayment($dto);
    }
}
