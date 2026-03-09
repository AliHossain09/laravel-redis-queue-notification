<?php

namespace App\Jobs;

use App\Events\NotificationSent;
use App\Models\NotificationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
        $notification = NotificationLog::create([
            'user_id' => $this->post->user_id,
            'message' => 'New post created: '.$this->post->title,
            'is_sent' => true,
        ]);

        try {
            event(new NotificationSent($notification));
        } catch (\Throwable $exception) {
            Log::warning('Notification broadcast skipped because the broadcaster is unavailable.', [
                'notification_id' => $notification->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
