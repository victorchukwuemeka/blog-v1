<?php

namespace App\Console\Commands;

use Embed\Embed;
use App\Models\Link;
use Illuminate\Console\Command;
use Embed\Http\NetworkException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
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
                try {
                    if ($imageUrl = (new Embed)->get($legacyLink->url)->image) {
                        Http::get($imageUrl)->throw();
                    }
                } catch (NetworkException|RequestException $e) {
                    $imageUrl = null;
                }

                Link::query()->updateOrCreate([
                    'url' => $legacyLink->url,
                ], [
                    'user_id' => $legacyLink->user_id,
                    'image_url' => $imageUrl,
                    'title' => $legacyLink->title,
                    'description' => $legacyLink->description,
                    'is_approved' => $legacyLink->is_approved,
                    'is_declined' => $legacyLink->is_declined,
                ]);

                $this->info("Synced link {$legacyLink->url}");
            });

        $this->info('All links have been synced.');
    }
}
