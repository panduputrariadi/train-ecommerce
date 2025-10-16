<?php

namespace App\Modules\User\DTOs;

use App\Base\BaseDto;

class ChangePasswordDto extends BaseDto
{
    public string $currentPassword;

    public string $newPassword;

    public string $newPasswordConfirmation;
}
