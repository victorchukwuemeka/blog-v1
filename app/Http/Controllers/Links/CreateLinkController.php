<?php

namespace App\Http\Controllers\Links;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class CreateLinkController extends Controller
{
    public function __invoke() : View
    {
        return view('links.create');
    }
}
