<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Jobs\SendPostNotification;
use App\Models\NotificationLog;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('posts', 60, function () {
            return Post::latest()->get();
        });

        $notifications = NotificationLog::latest()->take(5)->get();
        $notificationCount = NotificationLog::count();
        $pubSubMessages = $this->getPubSubMessages();

        return view('posts.index', [
            'posts' => $posts,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'pubSubMessages' => $pubSubMessages,
        ]);
    }

    public function store(Request $request)
    {
        $post = Post::create([
            'user_id' => 1,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        event(new PostCreated($post));

        SendPostNotification::dispatch($post);

        Cache::forget('posts');

        return redirect('/');
    }

    public function publish(Request $request)
    {
        $validated = $request->validate([
            'channel' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:255'],
        ]);

        \Illuminate\Support\Facades\Redis::publish($validated['channel'], $validated['message']);

        $payload = [
            'id' => (string) Str::uuid(),
            'channel' => $validated['channel'],
            'message' => $validated['message'],
            'received_at' => now()->toDateTimeString(),
            'source' => 'publisher',
        ];

        $messages = $this->getPubSubMessages();
        array_unshift($messages, $payload);
        $messages = collect($messages)
            ->unique(fn (array $message) => implode('|', [
                $message['id'] ?? '',
                $message['channel'] ?? '',
                $message['message'] ?? '',
                $message['received_at'] ?? '',
                $message['source'] ?? '',
            ]))
            ->take(8)
            ->values()
            ->all();

        $this->storePubSubMessages($messages);

        return redirect('/')->with('status', 'Pub/Sub message published. Subscriber চললে এটি received event হিসেবেও ধরা পড়বে।');
    }

    public function dashboardData(): JsonResponse
    {
        $notifications = NotificationLog::latest()->take(5)->get(['id', 'message', 'created_at']);
        $pubSubMessages = Cache::get('pubsub_messages', []);

        return response()->json([
            'notificationCount' => NotificationLog::count(),
            'notifications' => $notifications->map(fn (NotificationLog $notification) => [
                'id' => $notification->id,
                'message' => $notification->message,
                'created_at' => $notification->created_at?->diffForHumans(),
            ])->values(),
            'pubSubMessages' => $pubSubMessages,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post->update($validated);
        Cache::forget('posts');

        return redirect('/')->with('status', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Cache::forget('posts');

        return redirect('/')->with('status', 'Post deleted.');
    }

    public function updatePubSubMessage(Request $request, string $messageId)
    {
        $validated = $request->validate([
            'channel' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:255'],
        ]);

        $messages = collect($this->getPubSubMessages())
            ->map(function (array $item) use ($messageId, $validated) {
                if (($item['id'] ?? null) !== $messageId) {
                    return $item;
                }

                return array_merge($item, [
                    'channel' => $validated['channel'],
                    'message' => $validated['message'],
                    'received_at' => now()->toDateTimeString(),
                    'source' => 'edited',
                ]);
            })
            ->all();

        $this->storePubSubMessages($messages);

        return redirect('/')->with('status', 'Pub/Sub message updated.');
    }

    public function destroyPubSubMessage(string $messageId)
    {
        $messages = collect($this->getPubSubMessages())
            ->reject(fn (array $item) => ($item['id'] ?? null) === $messageId)
            ->values()
            ->all();

        $this->storePubSubMessages($messages);

        return redirect('/')->with('status', 'Pub/Sub message deleted.');
    }

    private function getPubSubMessages(): array
    {
        $messages = Cache::get('pubsub_messages', []);

        $normalized = collect($messages)
            ->map(function (array $message) {
                $message['id'] = $message['id'] ?? (string) Str::uuid();

                return $message;
            })
            ->take(8)
            ->values()
            ->all();

        $this->storePubSubMessages($normalized);

        return $normalized;
    }

    private function storePubSubMessages(array $messages): void
    {
        Cache::forever('pubsub_messages', array_values($messages));
    }
}
