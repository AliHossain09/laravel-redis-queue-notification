<?php

namespace App\Console\Commands;

use App\Events\RedisMessageReceived;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ListenRedisChannel extends Command
{
    protected $signature = 'app:redis-subscribe {channel=demo-channel}';

    protected $description = 'Listen to a Redis pub/sub channel and record incoming messages';

    public function handle(): int
    {
        $channel = (string) $this->argument('channel');

        $this->info("Listening to Redis channel [{$channel}]...");

        Redis::subscribe([$channel], function (string $message, string $incomingChannel) {
            $payload = [
                'channel' => $incomingChannel,
                'message' => $message,
                'received_at' => now()->toDateTimeString(),
            ];

            $messages = Cache::get('pubsub_messages', []);
            array_unshift($messages, $payload);
            $messages = array_slice($messages, 0, 8);
            Cache::forever('pubsub_messages', $messages);

            Log::info('Redis pub/sub message received', $payload);
            event(new RedisMessageReceived($payload));

            $this->line("[{$incomingChannel}] {$message}");
        });

        return self::SUCCESS;
    }
}
