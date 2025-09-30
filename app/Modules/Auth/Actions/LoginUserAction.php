<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\DTOs\LoginUserDto;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Request\LoginUserRequest;
use Illuminate\Support\Facades\DB;

class LoginUserAction
{
    public function execute(LoginUserRequest $request): array
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $dto = LoginUserDto::fromRequest($data);
            $user = User::where('email', $dto->email)->firstOrFail();

            $token = $user->createToken('api-token')->plainTextToken;
            DB::commit();
            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
