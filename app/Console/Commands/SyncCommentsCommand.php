<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// This is a temporary command that I'll remove once I'm in production.
class SyncCommentsCommand extends Command
{
    protected $signature = 'app:sync-comments';

    protected $description = 'Fetch comments from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing comments…');

        DB::connection('legacy')
            ->table('comments')
            ->get()
            ->each(function (object $legacyComment) {
                Comment::query()->updateOrCreate([
                    'user_id' => $legacyComment->user_id,
                    'post_slug' => $legacyComment->post_slug,
                    'parent_id' => $legacyComment->parent_id,
                    'content' => $legacyComment->content,
                ], [
                    'modified_at' => $legacyComment->modified_at,
                ]);

                $this->info("Synced comment \"{$legacyComment->content}\"");
            });

        $this->info('All comments have been synced.');
    }
}
