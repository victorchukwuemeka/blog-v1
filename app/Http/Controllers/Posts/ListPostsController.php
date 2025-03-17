<?php

namespace App\Http\Controllers\Posts;

use Illuminate\View\View;
use App\Actions\Posts\ParsePost;
use App\Actions\Posts\ExpandPost;
use App\Actions\Posts\FetchPosts;
use App\Http\Controllers\Controller;
use Symfony\Component\Finder\SplFileInfo;

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
                ->map(function (SplFileInfo $file) {
                    return app(ExpandPost::class)->enrich(
                        app(ParsePost::class)->parse($file)
                    );
                })
                ->sortByDesc('published_at')
        )
            ->paginate(24);

        return view('posts.index', compact('posts'));
    }
}
