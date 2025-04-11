<?php

namespace App\Http\Controllers\Posts;

use Illuminate\View\View;
use App\Actions\Posts\ExpandPost;
use App\Http\Controllers\Controller;
use App\Actions\Posts\ParseMarkdownFile;

class ShowPostController extends Controller
{
    public function __invoke(string $slug) : View
    {
        $filepath = resource_path("markdown/posts/{$slug}.md");

        abort_if(! file_exists($filepath), 404);

        // Let's see when's the last time the file was modified.
        $timestamp = filemtime($filepath);

        // We use the timestamp in the cache key to bust the cache if the file has been modified.
        $cacheKey = "post_{$slug}_$timestamp";

        $post = cache()->rememberForever(
            $cacheKey,
            fn () => app(ExpandPost::class)->expand(
                app(ParseMarkdownFile::class)->parse($filepath)
            )
        );

        return view('posts.show', compact('post'));
    }
}
