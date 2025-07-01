<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Actions\RefreshUserData;

class RefreshUserDataCommand extends Command
{
    protected $signature = 'app:refresh-user-data {id}';

    protected $description = "Refresh the user's data from GitHub.";

    public function handle() : void
    {
        $id = $this->argument('id');

        app(RefreshUserData::class)->refresh(
            User::query()
                ->where('id', $id)
                ->orWhere('name', $id)
                ->orWhere('github_login', $id)
                ->firstOrFail()
        );

        $this->info('User data has been refreshed.');
    }
}
