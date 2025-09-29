<?php

namespace App\Modules\OTP\DTOs;

class SendOtpDtos
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email']
        );
    }
}
