<?php

namespace App\Modules\Auth\DTOs;

use Illuminate\Http\UploadedFile;

class CreateRegisterUserDto
{
    public string $name;
    public string $email;
    public string $password;
    public string $otp_id;
    public ?UploadedFile $photo;
    public string $phone;

    public function __construct(
        string $name,
        string $email,
        string $password,
        string $otp_id,
        ?UploadedFile $photo,
        string $phone
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->otp_id = $otp_id;
        $this->photo = $photo;
        $this->phone = $phone;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            otp_id: $data['otp_id'],
            photo: $data['photo'] ?? null,
            phone: $data['phone']
        );
    }
}
