<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::allowStrayRequests();
});

it('uploads an image to Cloudflare Images via real API', function () {
    actingAs(User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]))
        ->from(route('show-cloudflare-images-form'))
        ->post(route('upload-to-cloudflare-images'), [
            'image' => UploadedFile::fake()->image('image.jpg', 50, 50),
        ])
        ->assertRedirect(route('show-cloudflare-images-form'))
        ->assertSessionHas('success')
        ->assertSessionHas('url');
});
