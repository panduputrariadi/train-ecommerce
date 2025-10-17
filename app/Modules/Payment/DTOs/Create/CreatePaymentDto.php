<?php

namespace App\Modules\Payment\DTOs\Create;

use App\Base\BaseDto;
use App\Modules\Payment\Enum\PaymentMethod;
use Illuminate\Http\UploadedFile;

class CreatePaymentDto extends BaseDto
{
    // public ?int $orderId;

    public PaymentMethod $paymentMethodId;

    public ?int $paidAmount;

    public ?string $notes = null;

    public ?string $type_file = null;

    public ?UploadedFile $evidenceFile = null;

    public ?int $bankAccountId = null;
}
