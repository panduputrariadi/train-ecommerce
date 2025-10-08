<?php

namespace App\Modules\User\Action;

use App\Modules\Share\Models\User as ModelsUser;
use App\Modules\User\DTOs\ChangePasswordDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordAction
{
    public function execute(ChangePasswordDto $dto): ModelsUser
    {
        $user = Auth::user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['Unauthenticated.'],
            ]);
        }

        if (!Hash::check($dto->currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Old password is incorrect.'],
            ]);
        }

        $user->password = $dto->newPassword;
        $user->save();

        return $user;
    }
}
