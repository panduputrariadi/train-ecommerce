<?php

namespace App\Modules\Order\Policies;

use App\Modules\Order\Models\Order;
use App\Modules\Share\Models\User;

class OrderPolicy
{
    /**
     * Check if user can view order
     *
     * @param  User  $user  The user to check.
     * @param  Order  $order  The order to check.
     * @return bool Whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Check if user can download invoice of order
     *
     * @param  User  $user  The user to check.
     * @param  Order  $order  The order to check.
     * @return bool Whether the user can download the invoice.
     */
    public function downloadInvoice(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Check if user can update order
     *
     * @param  User  $user  The user to check.
     * @param  Order  $order  The order to check.
     * @return bool Whether the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Check if user can delete order
     *
     * @param  User  $user  The user to check
     * @param  Order  $order  The order to check
     * @return bool Whether the user can delete the order
     */
    public function delete(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

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
}
