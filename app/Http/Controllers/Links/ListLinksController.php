<?php

namespace App\Http\Controllers\Links;

use App\Models\Link;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ListLinksController extends Controller
{
    public function __invoke() : View
    {
        return view('links.index', [
            'links' => Link::query()
                ->with('user')
                ->approved()
                ->latest('is_approved')
                ->paginate(12),
        ]);
    }
}
