<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostNotification;
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

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $post = Post::create([
            'user_id' => 1,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        SendPostNotification::dispatch($post);

        Cache::forget('posts');

        return redirect('/');
    }
}
