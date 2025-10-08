<?php

namespace App\Modules\Customer\Action\Update;

use App\Modules\Share\Enum\UserStatus;
use App\Modules\Share\Models\UserProfile;
use Exception;

class UnblockCustomerAction
{
    public function execute(UserProfile $profile): UserProfile
    {
        $user = $profile->user;

        if (!$user->roles()->where('slug', 'like', 'customer.%')->exists()) {
            throw new Exception('Not a customer');
        }

        $user->update(['status' => UserStatus::ACTIVE]);

        return $user;
    }
}
