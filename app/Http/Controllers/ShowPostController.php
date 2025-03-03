<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Actions\Posts\ParsePost;

class ShowPostController extends Controller
{
    public function __invoke(string $slug) : View
    {
        $filepath = resource_path("markdown/posts/{$slug}.md");

        abort_if(! file_exists($filepath), 404);

        $timestamp = filemtime($filepath);

        $cacheKey = "post_{$slug}_$timestamp";

        $post = cache()->rememberForever(
            $cacheKey, fn () => app(ParsePost::class)->parse($filepath)
        );

        return view('posts.show', compact('post'));
    }
}
