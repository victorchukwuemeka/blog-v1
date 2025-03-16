<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:fresh')]
class FreshCommand extends Command
{
    use ConfirmableTrait, Prohibitable;

    protected $description = "Reset the application's state and fetch fresh data";

    public function handle() : int
    {
        if ($this->isProhibited() || ! $this->confirmToProceed()) {
            return Command::FAILURE;
        }

        $this->info('Migrating the database…');

        Artisan::call('migrate:fresh');

        $this->info('Database migrated successfully.');

        $this->info('Syncing analytics data…');

        Artisan::call('app:sync-analytics');

        $this->info('Analytics data synced successfully.');

        $this->info('Syncing users data…');

        Artisan::call('app:sync-users');

        $this->info('Users data synced successfully.');

        $this->info('Syncing categories data…');

        Artisan::call('app:sync-categories');

        $this->info('Categories data synced successfully.');

        $this->info('Syncing comments data…');

        Artisan::call('app:sync-comments');

        $this->info('Comments data synced successfully.');

        $this->info('Syncing links data…');

        Artisan::call('app:sync-links');

        $this->info('Links data synced successfully.');

        return Command::SUCCESS;
    }
}
