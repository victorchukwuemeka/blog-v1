<?php

use function Pest\Laravel\get;

use App\Http\Middleware\TrackVisit;

use function Pest\Laravel\withoutMiddleware;

it("does not include Pirsch's script outside production", function () {
    get('/')
        ->assertDontSee('src="https://api.pirsch.io/pa.js"', escape: false);
});

it("includes Pirsch's script in production", function () {
    config(['app.env' => 'production']);

    withoutMiddleware(TrackVisit::class);

    get('/')
        ->assertSee('src="https://api.pirsch.io/pa.js"', escape: false);
});

it('signals the Atom feed', function () {
    get('/')
        ->assertSee('<link rel="alternate" type="application/atom+xml"', escape: false);
});
