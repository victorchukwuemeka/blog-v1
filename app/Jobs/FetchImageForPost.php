<?php

namespace App\Jobs;

use App\Str;
use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchImageForPost implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
    ) {}

    public function handle() : void
    {
        if (! app()->runningUnitTests()) {
            $image = Http::get('https://picsum.photos/1280/720')
                ->throw()
                ->body();

            Storage::disk('public')->put($path = '/images/posts/' . Str::random() . '.jpg', $image);
        } else {
            $path = null;
        }

        $this->post->update([
            'image_path' => $path,
            'image_disk' => $path ? 'public' : null,
        ]);
    }
}
