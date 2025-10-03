<?php

use App\Models\Job;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('lists jobs', function () {
    get(route('jobs.index'))
        ->assertOk()
        ->assertViewIs('jobs.index')
        ->assertViewHas('jobs', fn(LengthAwarePaginator $jobs) => true);
});

it('orders jobs the most recent first', function () {
    $older = Job::factory()->create(['created_at' => now()->subDays(2)]);
    $newer = Job::factory()->create(['created_at' => now()->subDay()]);

    $response = get(route('jobs.index'))
        ->assertOk();

    $response->assertViewHas('jobs', function (LengthAwarePaginator $paginator) use ($older, $newer) {
        $ids = collect($paginator->items())->pluck('id');

        return $ids->first() === $newer->id
            && $ids->contains($older->id);
    });
});

it('paginates 12 jobs per page', function () {
    Job::factory(25)->create();

    get(route('jobs.index'))
        ->assertOk()
        ->assertViewHas('jobs', fn(LengthAwarePaginator $p) => 12 === $p->perPage() && 12 === $p->count());

    get(route('jobs.index', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('jobs', fn(LengthAwarePaginator $p) => 2 === $p->currentPage() && 12 === $p->count());

    get(route('jobs.index', ['page' => 3]))
        ->assertOk()
        ->assertViewHas('jobs', fn(LengthAwarePaginator $p) => 3 === $p->currentPage() && 1 === $p->count());
});
