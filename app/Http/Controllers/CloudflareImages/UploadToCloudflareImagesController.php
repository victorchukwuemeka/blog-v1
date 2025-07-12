<?php

namespace App\Http\Controllers\CloudflareImages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Container\Attributes\Config;

class UploadToCloudflareImagesController extends Controller
{
    public function __invoke(
        #[Config('services.cloudflare_images.token')]
        string $token,
        Request $request
    ) : RedirectResponse {
        $request->validate([
            'image' => 'required|image',
        ]);

        $response = Http::withToken($token)
            ->asMultipart()
            ->attach(
                'file',
                fopen($file = $request->file('image'), 'r'),
                $file->getClientOriginalName()
            )
            ->post(sprintf(
                'https://api.cloudflare.com/client/v4/accounts/%s/images/v1',
                config('services.cloudflare_images.account_id'),
            ));

        if (! $response->ok()) {
            return back()->withErrors([
                'image' => $response->body(),
            ]);
        }

        return back()->with([
            'success' => 'Your image was successfully uploaded to Cloudflare Images.',
            'url' => $response->json('result')['variants'][0] ?? null,
        ]);
    }
}
