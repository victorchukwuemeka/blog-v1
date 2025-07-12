<?php

use function Pest\Laravel\get;

it('shows the advertising landing page', function () {
    get(route('advertise'))
        ->assertOk()
        ->assertViewIs('advertise')
        ->assertViewHas('views')
        ->assertViewHas('sessions')
        ->assertViewHas('desktop');
});
