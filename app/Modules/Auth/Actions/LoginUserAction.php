<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\DTOs\LoginUserDto;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Request\LoginUserRequest;

class LoginUserAction
{
    public function execute(LoginUserRequest $request): array
    {
        try {
            $dto = $request->validatedLogin();
            $user = User::where('email', $dto->email)->firstOrFail();

            $token = $user->createToken('api-token')->plainTextToken;
            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
