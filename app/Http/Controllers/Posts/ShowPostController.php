<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowPostController extends Controller
{
    public function __invoke(Request $request, Post $post) : View
    {
        if (! $request->user()?->isAdmin() && ! $post->published_at) {
            abort(404);
        }

        return view('posts.show', compact('post') + [
            'latestComment' => $post->comments()
                ->whereRelation('user', 'github_login', '!=', 'benjamincrozat')
                ->latest()
                ->first(),
        ]);
    }
}
