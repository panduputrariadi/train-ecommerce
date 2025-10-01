<?php

namespace App\Modules\OTP\DTOs;

class ForgetPasswordDtos
{
    public string $email;
    // public ?int $otp_id;

    public function __construct(string $email)
    {
        $this->email = $email;
        // $this->otp_id = $otp_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            // otp_id: $data['otp_id'] ?? null
        );
    }
}
