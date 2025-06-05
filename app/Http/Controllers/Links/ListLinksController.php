<?php

namespace App\Http\Controllers\Links;

use App\Models\Link;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class ListLinksController extends Controller
{
    public function __invoke() : View
    {
        $distinctUsersQuery = Link::query()
            ->select('user_id')
            ->distinct('user_id')
            ->whereRelation('user', fn (Builder $query) => $query->whereNotIn('email', ['benjamincrozat@me.com']))
            ->approved();

        return view('links.index', [
            'distinctUserAvatars' => $distinctUsersQuery
                ->whereRelation('user', fn (Builder $query) => $query->whereNotNull('avatar'))
                ->inRandomOrder()
                ->limit(10)
                ->get()
                ->map(fn (Link $link) => $link->user->avatar),

            'distinctUsersCount' => $distinctUsersQuery->count(),

            'links' => Link::query()
                ->approved()
                ->latest('is_approved')
                ->paginate(12),
        ]);
    }
}
