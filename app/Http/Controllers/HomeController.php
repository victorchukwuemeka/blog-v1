<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Actions\Posts\ListPosts;

class HomeController extends Controller
{
    public function __invoke() : View
    {
        // Get the timestamp for the most recent modified file.
        $timestamp = max(
            array_map(
                filemtime(...),
                glob(resource_path('markdown/posts') . '/*.md')
            )
        );

        $key = "latest_posts_$timestamp";

        $latest = cache()->rememberForever(
            $key, fn () => app(ListPosts::class)
                ->list()
                ->take(12)
        );

        return view('home', compact('latest'));
    }
}
