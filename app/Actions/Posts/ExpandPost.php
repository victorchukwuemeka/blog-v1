<?php

namespace App\Actions\Posts;

use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ExpandPost
{
    public function expand(array $post) : array
    {
        $post['categories'] = DB::table('category_post')
            ->where('post_slug', $post['slug'])
            ->pluck('category_id')
            ->map(Category::query()->find(...))
            ->filter()
            ->values();

        $post['comments_count'] = Comment::query()
            ->where('post_slug', $post['slug'])
            ->count();

        return $post;
    }
}
