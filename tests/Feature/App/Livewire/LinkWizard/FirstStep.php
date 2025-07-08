<?php

use App\Models\Link;
use Illuminate\Http\Client\Request;

use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Http;
use App\Livewire\LinkWizard\FirstStep;

it('submits the link', function () {
    Http::fake();

    livewire(FirstStep::class)
        ->set('url', 'https://example.com')
        ->call('submit')
        ->assertDispatched('nextStep');

    Http::assertSent(function (Request $request) {
        return 'https://example.com' === $request->url();
    });
});

it('requires a URL', function () {
    livewire(FirstStep::class)
        ->call('submit')
        ->assertHasErrors(['url' => 'required']);
});

it('requires a valid URL', function () {
    livewire(FirstStep::class)
        ->set('url', 'example')
        ->call('submit')
        ->assertHasErrors(['url' => 'url']);
});

it('ensures the URL is unique', function () {
    Link::factory()->create(['url' => 'https://example.com']);

    livewire(FirstStep::class)
        ->set('url', 'https://example.com')
        ->call('submit')
        ->assertHasErrors(['url' => 'unique']);
});
