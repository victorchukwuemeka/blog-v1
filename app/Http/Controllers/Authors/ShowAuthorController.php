<?php

namespace App\Http\Controllers\Authors;

use App\Models\Link;
use App\Models\User;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowAuthorController extends Controller
{
    public function __invoke(User $user) : View
    {
        return view('authors.show', [
            'author' => $user,

            'posts' => $user->posts()
                ->latest('published_at')
                ->published()
                ->paginate(12),

            'links' => Link::query()
                ->latest('is_approved')
                ->approved()
                ->paginate(12),
        ]);
    }
}
