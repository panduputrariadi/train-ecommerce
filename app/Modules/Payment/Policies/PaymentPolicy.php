<?php

namespace App\Modules\Payment\Policies;

use App\Modules\Order\Models\Order;
use App\Modules\Share\Enum\UserRole;
use App\Modules\Share\Models\User;

class PaymentPolicy
{
    /**
     * Check if user can make payment for order
     *
     * @param  User  $user  The user to check
     * @param  Order  $order  The order to check
     * @return bool Whether the user can make payment for the order
     */
    public function storePayment(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Check if user has any admin role
     *
     * @param  User  $user  The user to check
     * @return bool Whether the user has any admin role
     */
    public function paymentDecission(User $user): bool
    {
        $userRoleValues = $user->roles()->pluck('name')->toArray();

        $adminRoleValues = UserRole::adminRoles();

        $hasAdminRole = !empty(array_intersect($userRoleValues, $adminRoleValues));

        return $hasAdminRole;
    }
}
