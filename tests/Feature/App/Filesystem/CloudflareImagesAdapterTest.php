<?php

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(fn () => Http::allowStrayRequests());

it('uploads an image, returns a public URL, and deletes it using the Cloudflare Images adapter', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    $id = Str::random(20);

    $file = UploadedFile::fake()->image('image.jpg', 50, 50);

    expect($disk->putFileAs('', $file, $id))->toBeString();

    expect($disk->url($id))->toContain($id);

    expect($disk->delete($id))->toBeTrue();
});

it('reads an uploaded image both as a string and as a stream using the Cloudflare Images adapter', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    $id = Str::random(20);

    $file = UploadedFile::fake()->image('image.jpg', 50, 50);

    $disk->put($id, file_get_contents($file->getPathname()));

    expect($disk->get($id))->toBeString();

    $stream = $disk->readStream($id);

    expect($stream)->toBeResource();

    fclose($stream);

    expect($disk->delete($id))->toBeTrue();
});

it('throws the proper exceptions for unsupported operations on the Cloudflare Images adapter', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    /** @var \App\Filesystem\CloudflareImagesAdapter $adapter */
    $adapter = $disk->getAdapter();

    expect(fn () => $adapter->move('source', 'destination', new \League\Flysystem\Config))
        ->toThrow(\League\Flysystem\UnableToMoveFile::class);

    expect(fn () => $adapter->copy('source', 'destination', new \League\Flysystem\Config))
        ->toThrow(\League\Flysystem\UnableToCopyFile::class);

    expect(fn () => $adapter->setVisibility('source', 'private'))
        ->toThrow(\League\Flysystem\InvalidVisibilityProvided::class);

    expect(fn () => $adapter->createDirectory('foo', new \League\Flysystem\Config))
        ->toThrow(\League\Flysystem\UnableToCreateDirectory::class);
});
