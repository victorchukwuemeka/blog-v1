<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

it('uploads an image to Cloudflare Images via real API', function () {
    $accountId = config('services.cloudflare_images.account_id');
    $hasToken = (bool) config('services.cloudflare_images.token');
    $hasKey = (bool) config('services.cloudflare_images.key') && (bool) config('services.cloudflare_images.email');

    if (! $accountId || (! $hasToken && ! $hasKey)) {
        $this->markTestSkipped('Cloudflare Images credentials are not configured.');
    }

    // Allow real HTTP requests for this integration test.
    Http::preventStrayRequests(false);

    // We rely on the live endpoint defined in web.php / controller
    $response = $this->post(route('cloudflare-images.store'), [
        'image' => UploadedFile::fake()->image('test-avatar.jpg', 50, 50),
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $response->assertSessionHas('image_url');

    $url = session('image_url');

    expect($url)->not->toBeEmpty();
    expect(filter_var($url, FILTER_VALIDATE_URL))->not->toBeFalse();

    // Try to clean up: extract image ID and delete it.
    if (preg_match('#https?://[^/]+/([^/]+)/([^/]+)/#', $url, $m)) {
        $imageId = $m[2] ?? null;
        if ($imageId) {
            $headers = [];
            if ($hasToken) {
                $headers['Authorization'] = 'Bearer ' . config('services.cloudflare_images.token');
            } else {
                $headers['X-Auth-Key'] = config('services.cloudflare_images.key');
                $headers['X-Auth-Email'] = config('services.cloudflare_images.email');
            }

            $deleteResponse = Http::withHeaders($headers)->delete(
                sprintf('https://api.cloudflare.com/client/v4/accounts/%s/images/v1/%s', $accountId, $imageId),
            );

            expect($deleteResponse->status())->toBe(200);
            expect($deleteResponse->json('success'))->toBeTrue();
        }
    }
});
