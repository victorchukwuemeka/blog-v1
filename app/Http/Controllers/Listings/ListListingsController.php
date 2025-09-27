<?php

namespace App\Http\Controllers\Listings;

use App\Models\Listing;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ListListingsController extends Controller
{
    public function __invoke() : View
    {
        return view('listings.index', [
            'listings' => Listing::query()
                ->latest('published_on')
                ->paginate(12),
        ]);
    }
}
