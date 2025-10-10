<?php

use App\Jobs\TrackEvent as TrackEventJob;

it('delegates handling to the TrackEvent action with correct payload', function () {
    $action = Mockery::mock(\App\Actions\TrackEvent::class);
    $action->shouldReceive('track')->once()->with(
        'signup',
        ['plan' => 'pro'],
        'https://example.com/pricing',
        '203.0.113.10',
        'UA',
        'en-US',
        'https://google.com'
    );

    app()->instance(\App\Actions\TrackEvent::class, $action);

    $job = new TrackEventJob(
        'signup',
        ['plan' => 'pro'],
        'https://example.com/pricing',
        '203.0.113.10',
        'UA',
        'en-US',
        'https://google.com'
    );

    $job->handle();
});
