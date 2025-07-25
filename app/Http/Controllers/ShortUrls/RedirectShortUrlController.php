<?php

namespace App\Http\Controllers\ShortUrls;

use App\Jobs\TrackEvent;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RedirectShortUrlController extends Controller
{
    public function __invoke(Request $request, string $code) : RedirectResponse
    {
        $shortUrl = ShortUrl::query()
            ->where('code', $code)
            ->firstOrFail();

        if (! empty($url = $request->url()) &&
            ($ip = $request->ip()) &&
            ($userAgent = $request->userAgent())) {
            TrackEvent::dispatchAfterResponse(
                'Clicked on short URL',
                $request->fullUrl(),
                $ip,
                $userAgent,
                $request->header('Accept-Language', ''),
                $request->header('Referer', ''),
            );
        }

        return redirect()->away($shortUrl->url);
    }
}
