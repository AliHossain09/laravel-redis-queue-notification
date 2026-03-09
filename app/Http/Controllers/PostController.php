<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Jobs\SendPostNotification;
use App\Models\NotificationLog;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('posts', 60, function () {
            return Post::latest()->get();
        });

        $notifications = NotificationLog::latest()->take(5)->get();
        $pubSubMessages = Cache::get('pubsub_messages', []);

        return view('posts.index', [
            'posts' => $posts,
            'notifications' => $notifications,
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

        return redirect('/')->with('status', 'Pub/Sub message published.');
    }
}
