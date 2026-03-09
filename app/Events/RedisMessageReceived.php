<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RedisMessageReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $message)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('pubsub')];
    }

    public function broadcastAs(): string
    {
        return 'redis.message.received';
    }

    public function broadcastWith(): array
    {
        return $this->message;
    }
}
