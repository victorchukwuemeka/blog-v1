<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\View\View;
use App\Actions\Posts\ListMarkdownFiles;
use App\Actions\Posts\ParseMarkdownFile;

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

        $latest = cache()->rememberForever($key, function () {
            return app(ListMarkdownFiles::class)
                ->fetch()
                ->map(app(ParseMarkdownFile::class)->parse(...))
                ->sortByDesc('published_at')
                ->take(12)
                ->map(function (array $post) {
                    $post['comments_count'] = Comment::query()
                        ->where('post_slug', $post['slug'])
                        ->count();

                    return $post;
                });
        });

        return view('home', compact('latest'));
    }
}
