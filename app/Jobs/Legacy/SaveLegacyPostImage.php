<?php

namespace App\Jobs\Legacy;

use App\Str;
use Exception;
use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

// This job doesn't have tests as it will be removed once in production.
class SaveLegacyPostImage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected object $legacyPost,
        protected Post $post,
    ) {}

    public function handle() : void
    {
        if ($legacyImageUrl = $this->legacyPost->image) {
            $imageUrl = str_replace([
                'https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/',
                'https://res.cloudinary.com/benjamincrozat-com/image/fetch/',
            ], '', $legacyImageUrl);

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

        $this->post->update([
            'image_path' => $imagePath ?? null,
            'image_disk' => ! empty($imagePath) ? 'public' : null,
        ]);
    }
}
