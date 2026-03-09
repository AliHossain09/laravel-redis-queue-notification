<?php

namespace App\Events;

use App\Models\NotificationLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public NotificationLog $notification)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('notifications')];
    }

    public function broadcastAs(): string
    {
        return 'notification.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'message' => $this->notification->message,
            'is_sent' => $this->notification->is_sent,
            'created_at' => $this->notification->created_at?->toDateTimeString(),
        ];
    }
}
