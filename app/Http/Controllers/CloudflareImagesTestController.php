<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CloudflareImagesTestController extends Controller
{
    /**
     * Display the Cloudflare Images upload test page.
     */
    public function show()
    {
        return view('cloudflare-images-test');
    }

    /**
     * Handle the image upload to Cloudflare Images.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image'],
        ]);

        $file = $request->file('image');

        $headers = [];

        if ($token = config('services.cloudflare_images.token')) {
            $headers['Authorization'] = 'Bearer ' . $token;
        } elseif ($key = config('services.cloudflare_images.key')) {
            $headers['X-Auth-Key'] = $key;
            $headers['X-Auth-Email'] = config('services.cloudflare_images.email');
        }

        $response = Http::asMultipart()
            ->withHeaders($headers)
            ->attach(
                'file',
                fopen($file->getPathname(), 'r'),
                $file->getClientOriginalName()
            )
            ->post(sprintf(
                'https://api.cloudflare.com/client/v4/accounts/%s/images/v1',
                config('services.cloudflare_images.account_id'),
            ));

        if (! $response->ok()) {
            return back()->withErrors([
                'image' => 'Upload failed: ' . $response->body(),
            ]);
        }

        $result = $response->json('result');

        return back()->with([
            'success' => 'Image uploaded successfully!',
            'image_url' => $result['variants'][0] ?? null,
        ]);
    }
}
