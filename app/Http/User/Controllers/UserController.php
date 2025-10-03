<?php

namespace App\Http\User\Controllers;

use App\Http\Controllers\Controller;
use App\Http\User\Resources\UserProfileResource;
use App\Modules\User\Action\UserProfile;

class UserController extends Controller
{
    public function getProfile()
    {
        $user = new UserProfile;

        return new UserProfileResource($user);
    }
}
