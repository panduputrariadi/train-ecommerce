<?php

namespace App\Modules\Payment\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PaymentGroupBroadcast implements ShouldBroadcast
{
    use SerializesModels;

    public string $notificationId;
    public int|string $userId;
    public string $eventType;
    public array $payload;

    public function __construct(string $notificationId, $userId, string $eventType, array $payload)
    {
        $this->notificationId = $notificationId;
        $this->userId = $userId;
        $this->eventType = $eventType;
        $this->payload = $payload;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'event.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificationId,
            'event_type' => $this->eventType,
            'data' => $this->payload,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
