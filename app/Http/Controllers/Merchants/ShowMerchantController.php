<?php

namespace App\Http\Controllers\Merchants;

use Illuminate\Support\Uri;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ShowMerchantController extends Controller
{
    public function __invoke(string $slug) : RedirectResponse
    {
        abort_if(
            ! $merchantLink = collect(config('merchants'))
                ->flatMap(function (array $items) {
                    return collect($items)->map(
                        fn (mixed $item) => $item['link'] ?? $item
                    );
                })
                ->get($slug),
            404
        );

        $urlToRedirectTo = Uri::of($merchantLink)
            ->withQuery(request()->all());

        return redirect()->away($urlToRedirectTo);
    }
}
