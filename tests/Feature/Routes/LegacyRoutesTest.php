<?php

use function Pest\Laravel\get;

it('redirects /media-kit to /advertise with 301', function () {
    get('/media-kit')
        ->assertStatus(301)
        ->assertRedirect(route('advertise'));
});

it('redirects /nobinge to external URL with 301', function () {
    get('/nobinge')
        ->assertStatus(301)
        ->assertRedirect('https://nobinge.ai');
});

it('redirects /deals to /tools with 301', function () {
    get('/deals')
        ->assertStatus(301)
        ->assertRedirect(route('tools.index'));
});
