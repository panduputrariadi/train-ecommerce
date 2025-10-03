<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\Enum\UseOtp;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\ForgetPasswordRequest;
use App\Modules\OTP\Service\OtpService;

class ForgetPasswordAction
{
    public function __construct(private OtpService $service) {}

    public function execute(ForgetPasswordRequest $request): Otp
    {
        $dto = $request->validatedDto();

        return $this->service->generateOtp(
            email: $dto->email,
            usedFor: UseOtp::USED_FOR_FORGOT_PASSWORD
        );
    }
}
