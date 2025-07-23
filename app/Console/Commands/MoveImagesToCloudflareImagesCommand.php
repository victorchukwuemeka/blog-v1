<?php

namespace App\Console\Commands;

use App\Models\Post;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveImagesToCloudflareImagesCommand extends Command
{
    protected $signature = 'app:move-images-to-cloudflare-images {slug? : The slug of the post to process}';

    protected $description = 'Move post images from the public disk to Cloudflare Images.';

    public function handle() : void
    {
        if ($slug = $this->argument('slug')) {
            $post = Post::query()->where('slug', $slug)->firstOrFail();

            $this->processPost($post);

            return;
        }

        Post::query()
            ->where('image_disk', 'public')
            ->whereNotNull('image_path')
            ->get()
            ->each($this->processPost(...));

        $this->info('All eligible images have been processed.');
    }

    protected function processPost(Post $post) : void
    {
        $this->info("Processing post #{$post->id}…");

        $path = $post->image_path;

        $publicDisk = Storage::disk('public');

        if (! $publicDisk->exists($path)) {
            $this->warn("Image not found for post #{$post->id} at '{$path}'. Skipping…");

            return;
        }

        $contents = $publicDisk->get($path);

        try {
            $image = Image::load($publicDisk->path($path));

            // Skip images that are way too large to handle
            if ($image->getWidth() > 12000 || $image->getHeight() > 12000) {
                $this->warn("Image for post #{$post->id} is too large ({$image->getWidth()}x{$image->getHeight()}). Skipping…");

                return;
            }

            // Resize very large images down to a manageable size before uploading
            if ($image->getWidth() > 6000 || $image->getHeight() > 6000) {
                $tmpPath = tempnam(sys_get_temp_dir(), 'cfimg_');

                $image->fit(Fit::Max, 6000, 6000)->save($tmpPath);

                $contents = file_get_contents($tmpPath);

                @unlink($tmpPath);
            }
        } catch (\Throwable $e) {
            // Could not process the file as an image (e.g., not an image or unsupported format).
            // We'll upload the original file contents instead of failing.
            $this->warn("Could not process image for post #{$post->id} at \"{$path}\": {$e->getMessage()}. Uploading original file…");
        }

        // Whether we resized the image or not, at this point `$contents` contains the data we want to store.
        Storage::disk('cloudflare-images')->put($path, $contents);

        $post->update(['image_disk' => 'cloudflare-images']);

        $this->info("Moved image for post \"{$post->title}\" (#{$post->id})");
    }
}
