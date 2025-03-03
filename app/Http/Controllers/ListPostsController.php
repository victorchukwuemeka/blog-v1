<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Actions\Posts\ParsePost;
use App\Actions\Posts\FetchPosts;

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
            $key,
            fn () => app(FetchPosts::class)
                ->fetch()
                ->map(app(ParsePost::class)->parse(...))
                ->sortByDesc('published_at')
        )
            ->paginate(24);

        return view('posts.index', compact('posts'));
    }
}
