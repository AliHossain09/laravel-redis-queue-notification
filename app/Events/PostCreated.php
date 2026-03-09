<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Post $post)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('posts')];
    }

    public function broadcastAs(): string
    {
        return 'post.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->post->id,
            'user_id' => $this->post->user_id,
            'title' => $this->post->title,
            'content' => $this->post->content,
            'created_at' => $this->post->created_at?->toDateTimeString(),
        ];
    }
}
