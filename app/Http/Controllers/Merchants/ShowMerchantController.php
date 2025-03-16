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
            ! $link = collect(config('merchants'))
                ->flatMap(fn (array $items) => $items)
                ->get($slug),
            404
        );

        $link = Uri::of($link)
            ->withQuery(request()->all());

        return redirect()->away($link);
    }
}
