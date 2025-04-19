<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

        DB::connection('legacy')
            ->table('posts')
            ->get()
            ->each(function (object $legacyPost) {
                $this->info("Syncing post \"{$legacyPost->title}\"");

                if ($imageUrl = str_replace([
                    'https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/',
                    'https://res.cloudinary.com/benjamincrozat-com/image/fetch/',
                ], '', $legacyPost->image)) {
                    $response = Http::get($imageUrl)->throw();

                    $extension = match ($response->header('Content-Type')) {
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                        default => throw new Exception("{$response->header('Content-Type')} isn't supported."),
                    };

                    Storage::disk('public')
                        ->put($imagePath = 'images/posts/' . Str::random(15) . ".$extension", $response->body());
                }

                Post::query()->updateOrCreate([
                    'id' => $legacyPost->id,
                ], [
                    'user_id' => 1,
                    'image_path' => $imagePath ?? null,
                    'image_disk' => ! empty($imagePath) ? 'public' : null,
                    'title' => $legacyPost->title,
                    'slug' => $legacyPost->slug,
                    'content' => $legacyPost->content,
                    'description' => $legacyPost->description,
                    'canonical_url' => $legacyPost->canonical,
                    'published_at' => $legacyPost->published_at,
                    'modified_at' => $legacyPost->modified_at,
                    'created_at' => $legacyPost->created_at,
                ]);

                $this->info("Synced post \"{$legacyPost->title}\"");
            });

        $this->info('All posts have been synced.');
    }
}
