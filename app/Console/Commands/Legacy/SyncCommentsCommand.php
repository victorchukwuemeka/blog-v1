<?php

namespace App\Console\Commands\Legacy;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(
    name: 'app:sync-comments',
    description: 'Fetch comments from the legacy database'
)]
class SyncCommentsCommand extends Command
{
    public function handle() : void
    {
        $this->info('Syncing comments…');

        DB::connection('legacy')
            ->table('comments')
            ->get()
            ->each(function (object $legacyComment) {
                if (! $postId = Post::query()->where('slug', $legacyComment->post_slug)->value('id')) {
                    return;
                }

                Comment::query()->updateOrCreate([
                    'id' => $legacyComment->id,
                ], [
                    'user_id' => $legacyComment->user_id,
                    'post_id' => $postId,
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
