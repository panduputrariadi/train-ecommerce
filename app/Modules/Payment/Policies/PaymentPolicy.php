<?php

namespace App\Modules\Payment\Policies;

use App\Modules\Share\Enum\UserRole;
use App\Modules\Share\Models\User;

class PaymentPolicy
{
    /**
     * Check if user has any admin role
     *
     * @param  User  $user  The user to check
     * @return bool Whether the user has any admin role
     */
    public function paymentDecission(User $user): bool
    {
        $userRoleValues = array_map('strtolower', $user->roles()->pluck('slug')->toArray());
        $adminRoleValues = array_map('strtolower', UserRole::adminRoles());

        return ! empty(array_intersect($userRoleValues, $adminRoleValues));
    }

}
