<?php

namespace App\Console\Commands\Legacy;

use App\Models\Link;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Legacy\ValidateLinkImage;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(
    name: 'app:sync-links',
    description: 'Fetch links from the legacy database'
)]
class SyncLinksCommand extends Command
{
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
