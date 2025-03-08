<?php

namespace App\Http\Controllers\Links;

use App\Models\Link;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowLinkController extends Controller
{
    public function __invoke(Link $link) : View
    {
        return view('links.show', compact('link'));
    }
}
