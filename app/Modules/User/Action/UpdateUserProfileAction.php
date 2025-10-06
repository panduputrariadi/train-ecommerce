<?php

namespace App\Modules\User\Action;

use App\Modules\Share\Models\User;
use App\Modules\User\Request\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileAction
{
    public function execute(UpdateProfileRequest $request): User
    {
        $user = Auth::user();
        $dto = $request->validatedDto();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['name' => $dto->name]
        );

        return $user->load('role');
    }
}
