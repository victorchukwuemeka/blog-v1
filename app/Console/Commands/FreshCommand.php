<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:fresh')]
class FreshCommand extends Command
{
    protected $description = "Reset the application's state and fetch fresh data";

    public function handle() : int
    {
        $this->info('Migrating the database…');

        Artisan::call('migrate:fresh');

        $this->info('Database migrated successfully.');

        $this->info('Syncing analytics data…');

        Artisan::call(SyncAnalyticsCommand::class);

        $this->info('Analytics data synced successfully.');

        $this->info('Syncing users data…');

        Artisan::call(SyncUsersCommand::class);

        $this->info('Users data synced successfully.');

        $this->info('Syncing posts data…');

        Artisan::call(SyncPostsCommand::class);

        $this->info('Posts data synced successfully.');

        $this->info('Syncing categories data…');

        Artisan::call(SyncCategoriesCommand::class);

        $this->info('Categories data synced successfully.');

        $this->info('Syncing comments data…');

        Artisan::call(SyncCommentsCommand::class);

        $this->info('Comments data synced successfully.');

        $this->info('Syncing links data…');

        Artisan::call(SyncLinksCommand::class);

        $this->info('Links data synced successfully.');

        $this->info('Syncing reactions data…');

        Artisan::call(SyncReactionsCommand::class);

        $this->info('Reactions data synced successfully.');

        Artisan::call('cache:clear');

        $this->info('Cache cleared successfully.');

        return Command::SUCCESS;
    }
}
