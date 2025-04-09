<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, \Closure $next, string ...$guards) : Response
    {
        // Make sure this is a valid visit to track.
        if ('production' === config('app.env') &&
            ! $request->hasHeader('X-Livewire') &&
            ! $request->wantsJson() &&
            'GET' === $request->method()) {
            defer(function () use ($request) {
                // These are needed for Pirsch's API so we
                // need to make sure they are available.
                if (
                    ($url = $request->url()) &&
                    ($ip = $request->ip()) &&
                    ($userAgent = $request->userAgent())
                ) {
                    app(\App\Actions\TrackVisit::class)->track(
                        $url,
                        $ip,
                        $userAgent,
                        $request->header('Accept-Language', ''),
                        $request->header('Referer', ''),
                    );
                }
            });
        }

        return $next($request);
    }
}
