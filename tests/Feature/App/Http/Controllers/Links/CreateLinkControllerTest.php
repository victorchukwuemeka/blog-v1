<?php

use function Pest\Laravel\get;

it('renders the create link view', function () {
    get(route('links.create'))
        ->assertOk()
        ->assertViewIs('links.create');
});
