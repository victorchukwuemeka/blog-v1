<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\withoutVite;
use function Pest\Laravel\withoutDefer;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

pest()
    ->extend(TestCase::class)
    ->use(LazilyRefreshDatabase::class)
    ->beforeEach(function () {
        withoutDefer();

        // Useful when running tests without Vite running.
        withoutVite();

        // Make sure our tests don't make any unwanted HTTP requests.
        Http::preventStrayRequests();
    })
    ->in('Feature');
