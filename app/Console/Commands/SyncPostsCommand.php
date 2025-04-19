<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use App\Jobs\SaveLegacyPostImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:sync-posts')]
class SyncPostsCommand extends Command
{
    protected $description = 'Fetch posts from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing posts…');

        Storage::disk('public')->deleteDirectory('images/posts');

        DB::connection('legacy')
            ->table('posts')
            ->get()
            ->each(function (object $legacyPost) {
                $this->info("Syncing post \"{$legacyPost->title}\"");

                $post = Post::query()->updateOrCreate([
                    'id' => $legacyPost->id,
                ], [
                    'user_id' => 1,
                    'title' => $legacyPost->title,
                    'slug' => $legacyPost->slug,
                    'content' => $legacyPost->content,
                    'description' => $legacyPost->description,
                    'canonical_url' => $legacyPost->canonical,
                    'published_at' => $legacyPost->published_at,
                    'modified_at' => $legacyPost->modified_at,
                    'created_at' => $legacyPost->created_at,
                ]);

                dispatch(new SaveLegacyPostImage($legacyPost, $post));

                $this->info("Synced post \"{$legacyPost->title}\"");
            });

        $this->info('All posts have been synced.');
    }
}
