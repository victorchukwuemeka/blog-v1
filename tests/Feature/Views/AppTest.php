<?php

use function Pest\Laravel\get;

use App\Http\Middleware\TrackVisit;

use function Pest\Laravel\withoutMiddleware;

it('has a default title', function () {
    get('/')
        ->assertSee('title', config('app.name'));
});

it('has a default description', function () {
    get('/')
        ->assertSee('description', 'The best blog about PHP, Laravel, AI, and every other topics involved in building software.');
});

it("does not include Pirsch's script outside production", function () {
    get('/')
        ->assertDontSee('src="https://api.pirsch.io/pa.js"', escape: false);
});

it("includes Pirsch's script in production", function () {
    config(['app.env' => 'production']);

    withoutMiddleware(TrackVisit::class);

    get('/')
        ->assertSee('https://api.pirsch.io/pa.js', escape: false);
});

it('signals the Atom feed', function () {
    get('/')
        ->assertSee('application/atom+xml', escape: false);
});
