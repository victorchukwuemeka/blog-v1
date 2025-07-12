<?php

namespace App\Http\Controllers\CloudflareImages;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowCloudflareImagesFormController extends Controller
{
    public function __invoke() : View
    {
        return view('cloudflare-images');
    }
}
