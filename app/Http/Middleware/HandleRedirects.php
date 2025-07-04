<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        $path = trim($request->path(), '/');

        // Only handle simple root-level slugs (no slash) to avoid interfering with other routes.
        if ('' !== $path && ! str_contains($path, '/')) {
            if ($redirect = Redirect::query()->where('from', $path)->first()) {
                $target = '/' . ltrim($redirect->to, '/');

                // Preserve query string if present.
                if ($request->getQueryString()) {
                    $target .= '?' . $request->getQueryString();
                }

                return redirect($target, status: 301);
            }
        }

        return $next($request);
    }
}
