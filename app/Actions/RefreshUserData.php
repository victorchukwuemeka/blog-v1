<?php

namespace App\Actions;

use Github\Client;
use App\Models\User;
use Github\Exception\ApiLimitExceedException;

class RefreshUserData
{
    /**
     * Refresh the user's data from GitHub.
     */
    public function refresh(User $user) : void
    {
        try {
            $data = app(Client::class)
                ->api('user')
                ->showById($user->github_id);

            $githubData = $user->github_data ?? [];
            $githubData['user'] = $data;

            $user->update([
                'github_data' => $githubData,
                'refreshed_at' => now(),
            ]);
        } catch (ApiLimitExceedException $e) {
        }
    }
}
