<?php

namespace App\Modules\Auth\DTOs;

use App\Base\BaseDto;
use Illuminate\Http\UploadedFile;

class CreateRegisterUserDto extends BaseDto
{
    public string $name;

    public string $email;

    public string $password;

    public string $otpId;

    public ?UploadedFile $photo;

    public string $phone;
}
