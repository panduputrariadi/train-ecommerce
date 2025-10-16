<?php

namespace App\Modules\User\Action;

use App\Modules\Share\Models\User;
use App\Modules\User\DTOs\UpdateProfileDto;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileAction
{
    public function execute(UpdateProfileDto $dto): User
    {
        $user = Auth::user();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['name' => $dto->name]
        );

        return $user->load('role');
    }
}
