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
            empty($link = config("merchants.services.$slug") ?? config("merchants.books.$slug")),
            404
        );

        $link = Uri::of($link)
            ->withQuery(request()->all());

        return redirect()->away($link);
    }
}
