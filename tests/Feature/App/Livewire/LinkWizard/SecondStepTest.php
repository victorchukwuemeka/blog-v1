<?php

use App\Str;
use App\Models\Link;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Livewire\LinkWizard\SecondStep;

use function Pest\Laravel\assertDatabaseHas;

use Illuminate\Support\Facades\Notification;
use App\Notifications\LinkWaitingForValidation;

it('submits the link and notifies the admin', function () {
    Notification::fake();

    $url = 'https://example.com';

    cache()->forever('embed_' . Str::slug($url, '_'), [
        'image_url' => 'https://example.com/image.png',
        'title' => 'Example title',
        'description' => 'Example description',
    ]);

    $user = User::factory()->create();

    $admin = User::factory()->create(['github_login' => 'benjamincrozat']);

    actingAs($user);

    livewire(SecondStep::class, [
        'url' => $url,
        'imageUrl' => 'https://example.com/image.png',
        'title' => 'Example title',
        'description' => 'Example description',
    ])
        ->assertDispatched('fetch')
        ->call('fetch')
        ->call('submit')
        ->assertRedirect(route('links.index', ['submitted' => true]));

    assertDatabaseHas(Link::class, [
        'url' => $url,
        'image_url' => 'https://example.com/image.png',
        'title' => 'Example title',
        'description' => 'Example description',
    ]);

    Notification::assertSentToTimes($admin, LinkWaitingForValidation::class, 1);
});
