<?php

namespace App\Jobs;

use Embed\Embed;
use App\Models\Link;
use Embed\Http\NetworkException;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\RequestException;

// This job doesn't have tests as it will be removed once in production.
class ImportLegacyLink implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected object $legacyLink,
    ) {}

    public function handle() : void
    {
        try {
            if ($imageUrl = app(Embed::class)->get($this->legacyLink->url)->image) {
                Http::get($imageUrl)->throw();
            }
        } catch (NetworkException|RequestException $e) {
            $imageUrl = null;
        }

        Link::query()->updateOrCreate([
            'url' => $this->legacyLink->url,
        ], [
            'user_id' => $this->legacyLink->user_id,
            'image_url' => $imageUrl,
            'title' => $this->legacyLink->title,
            'description' => $this->legacyLink->description,
            'is_approved' => $this->legacyLink->is_approved,
            'is_declined' => $this->legacyLink->is_declined,
        ]);
    }
}
