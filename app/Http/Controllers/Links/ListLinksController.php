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
            ->whereRelation('user', function (Builder $query) {
                $query
                    ->whereNotIn('email', [
                        'benjamincrozat@gmail.com',
                        'benjamincrozat@icloud.com',
                        'benjamincrozat@me.com',
                        'hello@benjamincrozat.com',
                    ])
                    ->whereNotNull('avatar');
            });

        return view('links.index', [
            'distinctUserAvatars' => $distinctUsersQuery
                ->approved()
                ->with('user')
                ->inRandomOrder()
                ->limit(10)
                ->get()
                ->map(fn (Link $link) => $link->user->avatar),

            'distinctUsersCount' => $distinctUsersQuery->count(),

            'links' => Link::query()
                ->with('user')
                ->latest('is_approved')
                ->approved()
                ->paginate(12),
        ]);
    }
}
