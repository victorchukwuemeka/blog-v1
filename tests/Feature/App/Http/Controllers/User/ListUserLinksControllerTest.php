<?php

use App\Models\Link;
use App\Models\User;

use function Pest\Laravel\actingAs;

use Illuminate\Pagination\LengthAwarePaginator;

it("lists the user's links no matter their status", function () {
    $user = User::factory()->create();

    Link::factory(3)->create([
        'user_id' => $user->id,
    ]);

    Link::factory(3)->approved()->create([
        'user_id' => $user->id,
    ]);

    Link::factory(3)->declined()->create([
        'user_id' => $user->id,
    ]);

    actingAs($user)
        ->get(route('user.links'))
        ->assertOk()
        ->assertViewIs('user.links')
        ->assertViewHas('links', fn (LengthAwarePaginator $links) => 9 === $links->count());
});
