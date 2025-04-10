<?php

namespace App\Http\Controllers\Posts;

use Illuminate\View\View;
use App\Actions\Posts\ListPosts;
use App\Http\Controllers\Controller;

class ListPostsController extends Controller
{
    public function __invoke() : View
    {
        $timestamp = max(
            array_map(
                filemtime(...),
                glob(resource_path('markdown/posts') . '/*.md')
            )
        );

        $key = "posts_$timestamp";

        $posts = cache()->rememberForever(
            $key, fn () => app(ListPosts::class)->list()
        );

        return view('posts.index', compact('posts'));
    }
}
