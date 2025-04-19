<?php

namespace App\Console\Commands;

use App\Jobs\ImportLegacyLink;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:sync-links')]
class SyncLinksCommand extends Command
{
    protected $description = 'Fetch links from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing links…');

        DB::connection('legacy')
            ->table('links')
            ->get()
            ->each(function (object $legacyLink) {
                dispatch(new ImportLegacyLink($legacyLink));

                $this->info("Synced link {$legacyLink->url}");
            });

        $this->info('All links have been synced.');
    }
}
