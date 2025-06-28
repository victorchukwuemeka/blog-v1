<?php

namespace App\Jobs\Legacy;

use Embed\Embed;
use App\Models\Link;
use Embed\Http\NetworkException;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\RequestException;

// This job doesn't have tests as it will be removed once in production.
class ValidateLinkImage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Link $link,
    ) {}

    public function handle() : void
    {
        try {
            if ($imageUrl = app(Embed::class)->get($this->link->url)->image) {
                Http::get($imageUrl)->throw();
            }
        } catch (NetworkException|RequestException $e) {
            $imageUrl = null;
        }

        $this->link->update(['image_url' => $imageUrl]);
    }
}
