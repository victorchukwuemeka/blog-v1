<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// This is a temporary command that I'll remove once I'm in production.
class SyncUsersCommand extends Command
{
    protected $signature = 'app:sync-users';

    protected $description = 'Fetch users from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing users…');

        DB::connection('legacy')
            ->table('users')
            ->get()
            ->each(function (object $legacyUser) {
                User::query()->updateOrCreate([
                    'id' => $legacyUser->id,
                    'email' => $legacyUser->email,
                ], [
                    'name' => $legacyUser->name,
                    'github_login' => $legacyUser->github_login,
                    'avatar' => json_decode($legacyUser->github_data, true)['avatar'] ?? null,
                    'github_data' => json_decode($legacyUser->github_data),
                    'created_at' => $legacyUser->created_at,
                    'updated_at' => $legacyUser->updated_at,
                ]);

                $this->info("Synced user {$legacyUser->email}");
            });

        $this->info('All users have been synced.');
    }
}
