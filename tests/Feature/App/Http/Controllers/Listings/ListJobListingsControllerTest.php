<?php

use App\Models\JobListing;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('lists listings', function () {
    get(route('job-listings.index'))
        ->assertOk()
        ->assertViewIs('job-listings.index')
        ->assertViewHas('jobListings', fn (LengthAwarePaginator $listings) => true);
});

it('orders listings by published_on desc', function () {
    $older = JobListing::factory()->create(['published_on' => now()->subDays(2)]);
    $newer = JobListing::factory()->create(['published_on' => now()->subDay()]);

    $response = get(route('job-listings.index'))
        ->assertOk();

    $response->assertViewHas('jobListings', function (LengthAwarePaginator $paginator) use ($older, $newer) {
        $ids = collect($paginator->items())->pluck('id');

        return $ids->first() === $newer->id
            && $ids->contains($older->id);
    });
});

it('paginates 12 listings per page', function () {
    JobListing::factory(25)->create();

    get(route('job-listings.index'))
        ->assertOk()
        ->assertViewHas('jobListings', fn (LengthAwarePaginator $p) => 12 === $p->perPage() && 12 === $p->count());

    get(route('job-listings.index', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('jobListings', fn (LengthAwarePaginator $p) => 2 === $p->currentPage() && 12 === $p->count());

    get(route('job-listings.index', ['page' => 3]))
        ->assertOk()
        ->assertViewHas('jobListings', fn (LengthAwarePaginator $p) => 3 === $p->currentPage() && 1 === $p->count());
});
