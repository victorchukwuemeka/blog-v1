<?php

namespace App\Actions;

use Github\Client;
use App\Models\User;

class RefreshUserData
{
    /**
     * Refresh the user's data from GitHub.
     */
    public function refresh(User $user) : void
    {
        $data = app(Client::class)
            ->api('user')
            ->show($user->github_login);

        $githubData = $user->github_data ?? [];
        $githubData['user'] = $data;

        $user->update([
            'github_data' => $githubData,
            'refreshed_at' => now(),
        ]);
    }
}
