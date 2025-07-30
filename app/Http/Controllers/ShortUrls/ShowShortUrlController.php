<?php

namespace App\Http\Controllers\ShortUrls;

use App\Jobs\TrackEvent;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowShortUrlController extends Controller
{
    public function __invoke(Request $request, string $code) : RedirectResponse
    {
        $shortUrl = ShortUrl::query()
            ->where('code', $code)
            ->firstOrFail();

        if (($ip = $request->ip()) &&
            ($userAgent = $request->userAgent())) {
            TrackEvent::dispatchAfterResponse(
                'Clicked on short URL',
                ['url' => $shortUrl->url],
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
