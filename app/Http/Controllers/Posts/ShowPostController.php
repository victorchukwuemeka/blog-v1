<?php

namespace App\Http\Controllers\Posts;

use Illuminate\View\View;
use App\Actions\Posts\ParsePost;
use App\Actions\Posts\ExpandPost;
use App\Http\Controllers\Controller;

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
            function () use ($filepath) {
                return app(ExpandPost::class)->expand(
                    app(ParsePost::class)->parse($filepath)
                );
            }
        );

        $readTime = ceil(str_word_count($post['content']) / 200);

        return view('posts.show', compact('post', 'readTime'));
    }
}
