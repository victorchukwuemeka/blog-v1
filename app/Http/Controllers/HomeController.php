<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke() : View
    {
        return view('home', [
            'latest' => Post::query()
                ->withCount('comments')
                ->latest('published_at')
                ->published()
                ->limit(12)
                ->get(),

            'about' => User::findOrFail(1)->biography,
        ]);
    }
}
