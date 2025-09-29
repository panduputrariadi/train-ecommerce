<?php

namespace App\Modules\OTP\DTOs;

class VerifyOtpDtos
{
    public string $otp;
    public int $id;

    public function __construct(string $otp, int $id)
    {
        $this->otp = $otp;
        $this->id = $id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            otp: $data['otp']
        );
    }
}
