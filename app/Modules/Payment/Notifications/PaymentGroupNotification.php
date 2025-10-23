<?php

namespace App\Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PaymentGroupNotification extends Notification
{
    use Queueable;

    protected string $eventType;
    protected array $payload;

    public function __construct(string $eventType, array $payload = [])
    {
        $this->eventType = $eventType;
        $this->payload = $payload;
    }

    public function via($notifiable): array
    {
        return ['paymentGroup'];
    }


    public function toArray($notifiable): array
    {
        return [
            'event_type' => $this->eventType,
            'payload' => $this->payload,
        ];
    }

    public function toEventGroup($notifiable): array
    {
        return [
            'event_type' => $this->eventType,
            'payload' => $this->payload,
        ];
    }
}
