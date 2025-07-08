<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ListPostsController extends Controller
{
    public function __invoke() : View
    {
        return view('posts.index', [
            'posts' => Post::query()
                ->latest('published_at')
                ->published()
                ->whereDoesntHave('link')
                ->paginate(24),
        ]);
    }
}
