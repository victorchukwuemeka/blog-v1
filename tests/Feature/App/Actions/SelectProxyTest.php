<?php

use App\Actions\SelectProxy;

it('returns a proxy', function () {
    expect(app(SelectProxy::class)->select())
        ->toContain('gate.smartproxy.com');
});

it('returns a country-specific proxy', function () {
    expect(app(SelectProxy::class)->select('fr'))
        ->toContain('fr.smartproxy.com');
});
