<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, Closure $next, string ...$guards) : Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response) : void
    {
        if ($this->shouldTrack($request)) {
            // These are needed for Pirsch's API so we need to make sure the values are not empty or null.
            if (! empty($url = $request->url()) &&
                ($ip = $request->ip()) &&
                ($userAgent = $request->userAgent())) {
                app(\App\Actions\TrackVisit::class)->track(
                    $url,
                    $ip,
                    $userAgent,
                    $request->header('Accept-Language', ''),
                    $request->header('Referer', ''),
                );
            }
        }
    }

    /**
     * Determine if the request should be tracked.
     *
     * Only track visits that meet all of the following:
     * 1. The app is running in production.
     * 2. The request is not from Livewire.
     * 3. The request does not expect a JSON response.
     * 4. The request is not a prefetch request.
     * 5. The request uses the GET method.
     * 6. The request is not from a crawler. (Pirsch already filters them out, but some may have slipped through.)
     * 7. The request is not from an admin.
     */
    protected function shouldTrack(Request $request) : bool
    {
        return 'production' === config('app.env') &&
            ! $request->hasHeader('X-Livewire') &&
            ! $request->wantsJson() &&
            ! $this->isPrefetch($request) &&
            'GET' === $request->method() &&
            ! app(CrawlerDetect::class)->isCrawler($request->userAgent()) &&
            ! $request->user()?->isAdmin();
    }

    protected function isPrefetch(Request $request) : bool
    {
        // According to RFC 9218, browsers should send a `Purpose: prefetch` header when prefetching.
        // Firefox historically used proprietary headers such as `X-Moz: prefetch`.
        // We consider any of these signals as a prefetch request.

        $purpose = strtolower($request->header('Purpose', ''));
        if ('prefetch' === $purpose) {
            return true;
        }

        $xPurpose = strtolower($request->header('X-Purpose', ''));
        if ('prefetch' === $xPurpose) {
            return true;
        }

        $xMoz = strtolower($request->header('X-Moz', ''));
        if ('prefetch' === $xMoz) {
            return true;
        }

        return false;
    }
}
