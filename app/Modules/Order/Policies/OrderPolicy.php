<?php

namespace App\Modules\Order\Policies;

use App\Modules\Order\Models\Order;
use App\Modules\Share\Models\User;

class OrderPolicy
{
    /**
     * Tentukan apakah user bisa melihat order ini.
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Tentukan apakah user bisa mendownload invoice order ini.
     */
    public function downloadInvoice(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Tentukan apakah user bisa mengupdate/mengelola order ini.
     */
    public function update(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Tentukan apakah user bisa menghapus order ini.
     */
    public function delete(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }
}
