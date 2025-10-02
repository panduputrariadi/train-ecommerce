<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\DTOs\LoginUserDto;
use App\Modules\Auth\Request\LoginUserRequest;
use App\Modules\Share\Models\User;

class LoginUserAction
{
    public function execute(LoginUserRequest $request): array
    {
        $dto = $request->validatedLogin();
        $user = User::where('email', $dto->email)->firstOrFail();

        $token = $user->createToken('api-token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
