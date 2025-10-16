<?php

namespace App\Http\User\Controllers;

use App\Http\Controllers\Controller;
use App\Http\User\Resources\UserProfileResource;
use App\Modules\User\Action\ChangePasswordAction;
use App\Modules\User\Action\UpdateUserProfileAction;
use App\Modules\User\Action\UserProfile;
use App\Modules\User\Request\ChangePasswordRequest;
use App\Modules\User\Request\UpdateProfileRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getProfile(): UserProfileResource
    {
        $action = new UserProfile;
        $user = $action->execute();

        return new UserProfileResource($user);
    }

    public function updateUser(UpdateProfileRequest $request, UpdateUserProfileAction $action): UserProfileResource
    {
        $dto = $request->validatedDto();
        $data = DB::transaction(fn () => $action->execute($dto));

        return new UserProfileResource($data);
    }

    public function changePassword(ChangePasswordRequest $request, ChangePasswordAction $action): UserProfileResource
    {
        $dto = $request->validatedDto();

        $user = DB::transaction(fn () => $action->execute($dto));

        return new UserProfileResource($user);
    }
}
