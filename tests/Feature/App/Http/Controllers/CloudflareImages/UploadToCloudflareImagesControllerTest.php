<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::allowStrayRequests());

it('lets admins upload an image to Cloudflare Images', function () {
    $user = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($user)
        ->from(route('show-cloudflare-images-form'))
        ->post(route('upload-to-cloudflare-images'), [
            'image' => UploadedFile::fake()->image('image.jpg', 50, 50),
        ])
        ->assertRedirect(route('show-cloudflare-images-form'))
        ->assertSessionHas('success')
        ->assertSessionHas('url');
});

it('does not let users upload an image to Cloudflare Images', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('upload-to-cloudflare-images'), [
            'image' => UploadedFile::fake()->image('image.jpg', 50, 50),
        ])
        ->assertForbidden();
});

it('does not let guests upload an image to Cloudflare Images', function () {
    postJson(route('upload-to-cloudflare-images'), [
        'image' => UploadedFile::fake()->image('image.jpg', 50, 50),
    ])
        ->assertUnauthorized();
});
