<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostNotification;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $post = Post::create([
            'user_id' => 1,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        SendPostNotification::dispatch($post);

        return back();
    }
}
