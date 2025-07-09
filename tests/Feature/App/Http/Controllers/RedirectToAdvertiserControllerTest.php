<?php

use function Pest\Laravel\get;

it('redirects to the advertiser and tracks the visit', function () {
    get(route('redirect-to-advertiser', 'sevalla-sidebar'))
        ->assertRedirect(config('advertisers.sevalla-sidebar'));
});
