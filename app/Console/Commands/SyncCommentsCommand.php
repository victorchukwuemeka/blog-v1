<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:sync-comments')]
class SyncCommentsCommand extends Command
{
    protected $description = 'Fetch comments from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing comments…');

        DB::connection('legacy')
            ->table('comments')
            ->get()
            ->each(function (object $legacyComment) {
                Comment::query()->updateOrCreate([
                    'id' => $legacyComment->id,
                ], [
                    'user_id' => $legacyComment->user_id,
                    'post_slug' => $legacyComment->post_slug,
                    'parent_id' => $legacyComment->parent_id,
                    'content' => $legacyComment->content,
                    'modified_at' => $legacyComment->modified_at,
                    'created_at' => $legacyComment->created_at,
                ]);

                $this->info("Synced comment \"{$legacyComment->content}\"");
            });

        $this->info('All comments have been synced.');
    }
}
