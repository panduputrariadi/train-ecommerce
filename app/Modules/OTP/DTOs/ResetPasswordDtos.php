<?php

namespace App\Modules\OTP\DTOs;

class ResetPasswordDtos
{
    public int $otpId;
    public string $password;

    public function __construct(int $otpId, string $password)
    {
        $this->otpId = $otpId;
        $this->password = $password;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            otpId: $data['otp_id'],
            password: $data['password']
        );
    }
}
