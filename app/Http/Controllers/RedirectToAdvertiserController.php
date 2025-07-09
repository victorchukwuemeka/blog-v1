<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class RedirectToAdvertiserController extends Controller
{
    public function __invoke(string $slug) : RedirectResponse
    {
        return redirect(config("advertisers.$slug"));
    }
}
