<?php

use function Pest\Laravel\artisan;

it('dumps from production and restores into local', function () {
    config()->set('database.default', 'mysql');
    config()->set('database.connections.production', array_merge(
        config('database.connections.mysql'),
        [
            'host' => '127.0.0.1',
            'database' => 'prod',
            'username' => 'root',
            'password' => '',
        ],
    ));

    artisan('app:db:pull --dry-run')
        ->expectsOutputToContain('Dry run: snapshot:create --connection=production')
        ->expectsOutputToContain('Dry run: snapshot:load')
        ->assertOk();
});
