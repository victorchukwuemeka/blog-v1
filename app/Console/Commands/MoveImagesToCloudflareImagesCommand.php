<?php

namespace App\Console\Commands;

use App\Models\Post;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Exceptions\UnsupportedImageFormat;

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
            ->cursor($this->processPost(...));

        $this->info('All eligible images have been processed.');
    }

    protected function processPost(Post $post) : void
    {
        $path = $post->image_path;

        $publicDisk = Storage::disk('public');

        if (! $publicDisk->exists($path)) {
            $this->warn("Image not found for post #{$post->id} at '{$path}'. Skipping…");

            return;
        }

        $contents = $publicDisk->get($path);

        try {
            $image = Image::load($publicDisk->path($path));

            if ($image->getWidth() > 12000 || $image->getHeight() > 12000) {
                $tmpPath = tempnam(sys_get_temp_dir(), 'cfimg_');

                $image->fit(Fit::Max, 12000, 12000)->save($tmpPath);

                $contents = file_get_contents($tmpPath);

                @unlink($tmpPath);
            }

            Storage::disk('cloudflare-images')->put($path, $contents);

            $post->update(['image_disk' => 'cloudflare-images']);

            $this->info("Moved image for post \"{$post->title}\" (#{$post->id})");
        } catch (UnsupportedImageFormat $e) {
            $this->warn("Unsupported image format for post #{$post->id} at '{$path}'. Skipping…");
        }
    }
}
