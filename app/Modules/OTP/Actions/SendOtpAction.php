<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\Enum\UseOtp;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\SendOtpRequest;
use App\Modules\OTP\Service\OtpService;

class SendOtpAction
{
    public function __construct(private OtpService $service)
    {

    }
    public function execute(SendOtpRequest $request): Otp
    {
        $dto = $request->getDto();
        return $this->service->generateOtp(
            email: $dto->email,
            usedFor: UseOtp::USED_FOR_REGISTER
        );
    }
}
