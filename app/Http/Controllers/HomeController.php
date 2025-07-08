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
            'popular' => Post::query()
                ->published()
                ->whereDoesntHave('link')
                ->where('sessions_count', '>', 0)
                ->orderBy('sessions_count', 'desc')
                ->limit(12)
                ->get(),

            'latest' => Post::query()
                ->latest('published_at')
                ->published()
                ->whereDoesntHave('link')
                ->limit(12)
                ->get(),

            'aboutUser' => User::first(),
        ]);
    }
}
