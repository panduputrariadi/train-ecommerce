<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\Request\LoginAdminRequest;
use App\Modules\Share\Models\User;

class LoginAdminAction
{
    public function execute(LoginAdminRequest $request): array
    {
        $dto = $request->validatedLogin();
        $user = User::where('email', $dto->email)->firstOrFail();

        $token = $user->createToken('api_token_user')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
