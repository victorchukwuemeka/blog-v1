<?php

namespace App\Console\Commands;

use App\Models\Reaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:sync-reactions')]
class SyncReactionsCommand extends Command
{
    protected $description = 'Fetch reactions from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing reactions…');

        DB::connection('legacy')
            ->table('reactions')
            ->get()
            ->each(function (object $legacyReaction) {
                Reaction::query()->updateOrCreate([
                    'id' => $legacyReaction->id,
                ], [
                    'user_id' => $legacyReaction->user_id,
                    'comment_id' => $legacyReaction->comment_id,
                    'emoji' => $legacyReaction->emoji,
                ]);

                $this->info("Synced reaction #{$legacyReaction->id}");
            });

        $this->info('All reactions have been synced.');
    }
}
