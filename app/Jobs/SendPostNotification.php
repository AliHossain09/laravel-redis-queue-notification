<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPostNotification implements ShouldQueue
{
    use Queueable;

    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        NotificationLog::create([
            'user_id' => $this->post->user_id,
            'message' => 'New post created: '.$this->post->title,
            'is_sent' => true,
        ]);
    }
}
