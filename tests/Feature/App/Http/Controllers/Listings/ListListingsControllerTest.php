<?php

use App\Models\Listing;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('lists listings', function () {
    get(route('listings.index'))
        ->assertOk()
        ->assertViewIs('listings.index')
        ->assertViewHas('listings', fn (LengthAwarePaginator $listings) => true);
});

it('orders listings by published_on desc', function () {
    $older = Listing::factory()->create(['published_on' => now()->subDays(2)]);
    $newer = Listing::factory()->create(['published_on' => now()->subDay()]);

    $response = get(route('listings.index'))
        ->assertOk();

    $response->assertViewHas('listings', function (LengthAwarePaginator $paginator) use ($older, $newer) {
        $ids = collect($paginator->items())->pluck('id');

        return $ids->first() === $newer->id
            && $ids->contains($older->id);
    });
});

it('paginates 12 listings per page', function () {
    Listing::factory(25)->create();

    get(route('listings.index'))
        ->assertOk()
        ->assertViewHas('listings', fn (LengthAwarePaginator $p) => 12 === $p->perPage() && 12 === $p->count());

    get(route('listings.index', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('listings', fn (LengthAwarePaginator $p) => 2 === $p->currentPage() && 12 === $p->count());

    get(route('listings.index', ['page' => 3]))
        ->assertOk()
        ->assertViewHas('listings', fn (LengthAwarePaginator $p) => 3 === $p->currentPage() && 1 === $p->count());
});
