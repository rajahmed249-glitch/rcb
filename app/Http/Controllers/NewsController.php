<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        return view('news.index', [
            'posts' => NewsPost::orderByDesc('published_at')->paginate(9),
        ]);
    }

    public function show(NewsPost $post)
    {
        $related = NewsPost::where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('news.show', ['post' => $post, 'related' => $related]);
    }
}
