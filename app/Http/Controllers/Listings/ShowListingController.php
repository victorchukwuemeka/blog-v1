<?php

namespace App\Http\Controllers\Listings;

use App\Models\Listing;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowListingController extends Controller
{
    public function __invoke(Listing $listing) : View
    {
        return view('listings.show', compact('listing'));
    }
}
