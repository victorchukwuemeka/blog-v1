<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthCallbackController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::query()->updateOrCreate(['email' => $githubUser->getEmail()], [
            'name' => $githubUser->getName() ?? $githubUser->getNickname(),
            'github_login' => $githubUser->getNickname(),
            'github_data' => (array) $githubUser,
            'email' => $githubUser->getEmail(),
        ]);

        auth()->login($user, true);

        return to_route('home')->with('status', 'You have been logged in.');
    }
}
