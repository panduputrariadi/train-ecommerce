<?php

namespace App\Modules\Payment\DTOs\Create;

use App\Base\BaseDto;
use Illuminate\Http\UploadedFile;

class CreatePaymentDto extends BaseDto
{
    public int $orderId;

    public int $paymentMethodId;

    public int $paidAmount;

    public ?string $notes = null;

    public ?string $type_file = null;

    public ?UploadedFile $evidenceFile = null;
}
