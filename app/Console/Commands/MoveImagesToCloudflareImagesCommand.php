<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:move-images-to-cloudflare-images',
    description: 'Move post images from the public disk to Cloudflare Images.'
)]
class MoveImagesToCloudflareImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Note: the signature is still required for the Laravel Artisan command
     * registration even though we're also using the PHP Attribute above.
     */
    protected $signature = 'app:move-images-to-cloudflare-images {slug? : The slug of the post to process}';

    protected $description = 'Move post images from the public disk to Cloudflare Images.';

    public function handle() : void
    {

        // If a slug was provided, process only that post.
        if ($slug = $this->argument('slug')) {

            /** @var \App\Models\Post $post */
            $post = Post::query()->where('slug', $slug)->firstOrFail();

            $this->processPost($post);

            return; // Done.
        }

        // Otherwise, process all eligible posts.
        Post::query()
            ->where('image_disk', 'public')
            ->whereNotNull('image_path')
            ->chunkById(100, function ($posts) {
                /** @var \App\Models\Post $post */
                foreach ($posts as $post) {
                    $this->processPost($post);
                }
            });

        $this->info('All eligible images have been processed.');
    }

    protected function processPost(Post $post) : void
    {
        $path = $post->image_path;

        // Skip if file is missing.
        if (! Storage::disk('public')->exists($path)) {
            $this->warn("Image not found for post #{$post->id} at '{$path}'. Skipping…");

            return;
        }

        $contents = Storage::disk('public')->get($path);

        // Upload to Cloudflare Images (overwriting if it already exists).
        Storage::disk('cloudflare-images')->put($path, $contents);

        // Delete original file on public disk.
        Storage::disk('public')->delete($path);

        // Update post record.
        $post->update(['image_disk' => 'cloudflare-images']);

        $this->info("Moved image for post \"{$post->title}\" (#{$post->id})");
    }
}
