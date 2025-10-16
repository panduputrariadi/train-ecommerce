<?php

namespace App\Modules\Customer\Action\Update;

use App\Modules\Share\Enum\UserStatus;
use App\Modules\Share\Models\UserProfile;
use Exception;

class BlockCustomerAction
{
    public function execute(UserProfile $profile): UserProfile
    {
        $user = $profile->user;

        if (! $user->roles()->where('slug', 'like', 'customer.%')->exists()) {
            throw new Exception('Not a customer');
        }

        if ($user->status == UserStatus::BLOCKED) {
            throw new Exception('Customer has blocked');
        }

        $user->update(['status' => UserStatus::BLOCKED]);

        return $user;
    }
}
