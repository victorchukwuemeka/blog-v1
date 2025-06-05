<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Jobs\ValidateLinkImage;
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
                $link = Link::query()->updateOrCreate([
                    'url' => $legacyLink->url,
                ], [
                    'user_id' => $legacyLink->user_id,
                    'title' => $legacyLink->title,
                    'description' => $legacyLink->description,
                    'is_approved' => $legacyLink->is_approved,
                    'is_declined' => $legacyLink->is_declined,
                ]);

                dispatch(new ValidateLinkImage($link));

                $this->info("Synced link {$legacyLink->url}");
            });

        $this->info('All links have been synced.');
    }
}
