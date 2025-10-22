<?php

namespace App\Modules\User\Action;

use App\Modules\Share\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfile
{
    public function execute(): User
    {
        $user = Auth::user();
        $user->load('profile', 'roles', 'addresses');

        return $user;
    }
}
