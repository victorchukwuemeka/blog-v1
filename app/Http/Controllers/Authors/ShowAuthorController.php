<?php

namespace App\Http\Controllers\Authors;

use App\Models\User;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowAuthorController extends Controller
{
    public function __invoke(User $user) : View
    {
        if ($user->posts->isEmpty()) {
            abort(404);
        }

        return view('authors.show', [
            'author' => $user,
        ]);
    }
}
