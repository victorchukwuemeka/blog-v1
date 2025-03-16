<?php

namespace App\Http\Controllers\Posts;

use App\Models\Comment;
use App\Models\Category;
use Illuminate\View\View;
use App\Actions\Posts\ParsePost;
use App\Actions\Posts\FetchPosts;
use Illuminate\Support\Facades\DB;
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
            $key,
            fn () => app(FetchPosts::class)
                ->fetch()
                ->map(app(ParsePost::class)->parse(...))
                ->map(function (array $post) {
                    $post['categories'] = DB::table('category_post')
                        ->where('post_slug', $post['slug'])
                        ->pluck('category_id')
                        ->map(fn (int $id) => Category::query()->find($id))
                        ->filter()
                        ->values();

                    $post['comments_count'] = Comment::query()
                        ->where('post_slug', $post['slug'])
                        ->count();

                    return $post;
                })
                ->sortByDesc('published_at')
        )
            ->paginate(24);

        return view('posts.index', compact('posts'));
    }
}
