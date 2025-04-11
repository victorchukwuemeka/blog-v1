<?php

namespace App\Actions\Posts;

use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ExpandPost
{
    public function expand(array $post) : array
    {
        $post['read_time'] = ceil(str_word_count($post['content']) / 200);

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
    }
}
