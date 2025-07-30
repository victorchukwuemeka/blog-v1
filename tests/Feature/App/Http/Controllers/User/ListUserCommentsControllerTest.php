<?php

use App\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\actingAs;

use Illuminate\Pagination\LengthAwarePaginator;

it("lists the user's comments", function () {
    $user = User::factory()
        ->hasComments(15)
        ->create();

    actingAs($user)
        ->get(route('user.comments'))
        ->assertOk()
        ->assertViewIs('user.comments')
        ->assertViewHas('comments', function (LengthAwarePaginator $comments) {
            expect($comments->count())->toBe(10);

            return true;
        });
});

it("doesn't allow guests", function () {
    getJson(route('user.comments'))
        ->assertUnauthorized();
});
