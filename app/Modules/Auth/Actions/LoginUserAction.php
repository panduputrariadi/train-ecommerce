<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\Request\LoginUserRequest;
use App\Modules\Share\Models\User;

class LoginUserAction
{
    /**
     * @return array{user: User, token: string}
     */
    public function execute(LoginUserRequest $request): array
    {
        $dto = $request->validatedLogin();
        $user = User::where('email', $dto->email)->firstOrFail();

        $token = $user->createToken(
            name: 'api_token_user',
            abilities: ['*'],
            expiresAt: now()->addDay()
        )->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
