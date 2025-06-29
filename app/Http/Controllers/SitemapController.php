<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        return redirect(Storage::disk('public')->url('sitemap.xml'), status: 301);
    }
}
