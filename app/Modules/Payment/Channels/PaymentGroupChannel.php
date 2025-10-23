<?php

namespace App\Modules\Payment\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel;
use App\Modules\Payment\Broadcasts\PaymentGroupBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PaymentGroupChannel extends DatabaseChannel
{

    /**
     * Send the given notification.
     *
     * @param  \Illuminate\Notifications\Notifiable  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification): void
    {
        if (!isset($notification->id)) {
            $notification->id = Str::uuid()->toString();
        }

        $storedNotification = parent::send($notifiable, $notification);

        $data = $notification->toEventGroup($notifiable);

        broadcast(new PaymentGroupBroadcast(
            $storedNotification->id ?? $notification->id,
            $notifiable->getKey(),
            $data['event_type'] ?? 'unknown',
            $data['payload'] ?? []
        ))->toOthers();

    }
}
