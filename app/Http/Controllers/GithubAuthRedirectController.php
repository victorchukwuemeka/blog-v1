<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthRedirectController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\GithubProvider */
        $github = Socialite::driver('github');

        return $github
            ->scopes(['user:email'])
            ->redirect();
    }
}
