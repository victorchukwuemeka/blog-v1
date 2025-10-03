<?php

use App\Actions\Scrape;
use App\Scraper\Webpage;
use Illuminate\Support\Facades\Process;

it('scrapes a given url', function () {
    Process::fake([
        '*' => Process::result(json_encode($fakeResult = [
            'url' => 'https://example.com',
            'imageUrl' => 'https://example.com/image.jpg',
            'title' => 'Lorem ipsum dolor sit amet.',
            'content' => 'Lorem ipsum dolor sit amet.',
        ])),
    ]);

    $webpage = app(Scrape::class)->scrape('https://example.com');

    expect($webpage)
        ->toBeInstanceOf(Webpage::class)
        ->and($webpage->url)->toBe($fakeResult['url'])
        ->and($webpage->imageUrl)->toBe($fakeResult['imageUrl'])
        ->and($webpage->title)->toBe($fakeResult['title'])
        ->and($webpage->content)->toBe($fakeResult['content']);
});

it('throws an unknown error exception if the process fails', function () {
    Process::fake([
        '*' => Process::result(errorOutput: 'Lorem ipsum dolor sit amet.', exitCode: 1),
    ]);

    expect(fn () => app(Scrape::class)->scrape('https://example.com'))
        ->toThrow(Exception::class, 'Lorem ipsum dolor sit amet.');
});

it('does not include proxy flags when no proxy is provided', function () {
    Process::fake([
        '*' => Process::result(json_encode([
            'url' => 'https://example.com',
            'imageUrl' => null,
            'title' => 'Example',
            'content' => '<p>Example</p>',
        ])),
    ]);

    app(Scrape::class)->scrape('https://example.com');

    Process::assertRan(function ($process) {
        $command = $process->command;

        return is_string($command)
            && str_contains($command, 'scraper.py')
            && ! str_contains($command, '--proxy')
            && ! str_contains($command, '--proxy-username')
            && ! str_contains($command, '--proxy-password');
    });
});

it('includes proxy flags and credentials when proxy is provided', function () {
    config()->set('services.smartproxy.proxy_username', 'user');
    config()->set('services.smartproxy.proxy_password', 'pass');

    Process::fake([
        '*' => Process::result(json_encode([
            'url' => 'https://example.com',
            'imageUrl' => null,
            'title' => 'Example',
            'content' => '<p>Example</p>',
        ])),
    ]);

    app(Scrape::class)->scrape('https://example.com', 'fr.smartproxy.com:10001');

    Process::assertRan(function ($process) {
        $command = $process->command;

        return is_string($command)
            && str_contains($command, '--proxy ')
            && str_contains($command, 'https://fr.smartproxy.com:10001')
            && str_contains($command, '--proxy-username ')
            && str_contains($command, '--proxy-password ');
    });
});

it('throws when scraper output is invalid JSON', function () {
    Process::fake([
        '*' => Process::result('not-json'),
    ]);

    expect(fn () => app(Scrape::class)->scrape('https://example.com'))
        ->toThrow(Exception::class, 'Invalid scraper output.');
});
