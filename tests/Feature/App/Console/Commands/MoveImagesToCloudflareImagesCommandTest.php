<?php

use App\Models\Post;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Storage;
use App\Console\Commands\MoveImagesToCloudflareImagesCommand;

it('moves post images from the public disk to the cloudflare-images disk', function () {
    // Fake both disks
    Storage::fake('public');
    Storage::fake('cloudflare-images');

    // Prepare a dummy image on the public disk
    $path = 'images/sample.jpg';
    $file = UploadedFile::fake()->image('sample.jpg', 50, 50);
    Storage::disk('public')->put($path, file_get_contents($file->getPathname()));

    /** @var \App\Models\Post $post */
    $post = Post::factory()->create([
        'image_path' => $path,
        'image_disk' => 'public',
    ]);

    artisan(MoveImagesToCloudflareImagesCommand::class)->assertSuccessful();

    // File should now exist on the cloudflare-images disk and be removed from public
    expect(Storage::disk('cloudflare-images')->exists($path))->toBeTrue();
    expect(Storage::disk('public')->exists($path))->toBeTrue();

    expect($post->fresh()->image_disk)->toBe('cloudflare-images');
});

it('skips posts whose images are already on the cloudflare-images disk', function () {
    Storage::fake('cloudflare-images');

    $path = 'images/already-there.jpg';
    $fileCf = UploadedFile::fake()->image('already-there.jpg', 50, 50);
    Storage::disk('cloudflare-images')->put($path, file_get_contents($fileCf->getPathname()));

    $post = Post::factory()->create([
        'image_path' => $path,
        'image_disk' => 'cloudflare-images',
    ]);

    artisan(MoveImagesToCloudflareImagesCommand::class)->assertSuccessful();

    // Ensure disk value remains unchanged
    expect($post->fresh()->image_disk)->toBe('cloudflare-images');
});

it('handles missing source images gracefully', function () {
    Storage::fake('public');
    Storage::fake('cloudflare-images');

    $missingPath = 'images/missing.jpg';

    // Do NOT create the file on the public disk.
    $post = Post::factory()->create([
        'image_path' => $missingPath,
        'image_disk' => 'public',
    ]);

    artisan(MoveImagesToCloudflareImagesCommand::class)->assertSuccessful();

    // Disk should remain unchanged and nothing on cloudflare-images
    expect($post->fresh()->image_disk)->toBe('public');
    expect(Storage::disk('cloudflare-images')->exists($missingPath))->toBeFalse();
});

it('processes only the specified post when a slug is provided', function () {
    Storage::fake('public');
    Storage::fake('cloudflare-images');

    // Post to migrate
    $slug = 'migrate-me';
    $pathToMove = 'images/migrate.jpg';
    $fileMigrate = UploadedFile::fake()->image('migrate.jpg', 60, 60);
    Storage::disk('public')->put($pathToMove, file_get_contents($fileMigrate->getPathname()));

    $migratePost = Post::factory()->create([
        'slug' => $slug,
        'image_path' => $pathToMove,
        'image_disk' => 'public',
    ]);

    // Post that should remain unchanged
    $stayPath = 'images/stay.jpg';
    $fileStay = UploadedFile::fake()->image('stay.jpg', 60, 60);
    Storage::disk('public')->put($stayPath, file_get_contents($fileStay->getPathname()));

    $stayPost = Post::factory()->create([
        'slug' => 'stay-put',
        'image_path' => $stayPath,
        'image_disk' => 'public',
    ]);

    artisan(MoveImagesToCloudflareImagesCommand::class, ['slug' => $slug])->assertSuccessful();

    // Migrated post should be moved
    expect(Storage::disk('cloudflare-images')->exists($pathToMove))->toBeTrue();
    expect(Storage::disk('public')->exists($pathToMove))->toBeTrue();
    expect($migratePost->fresh()->image_disk)->toBe('cloudflare-images');

    // Other post should stay on public disk
    expect(Storage::disk('cloudflare-images')->exists($stayPath))->toBeFalse();
    expect(Storage::disk('public')->exists($stayPath))->toBeTrue();
    expect($stayPost->fresh()->image_disk)->toBe('public');
});

it('uploads original file when image cannot be processed', function () {
    Storage::fake('public');
    Storage::fake('cloudflare-images');

    $badPath = 'images/bad.txt';
    Storage::disk('public')->put($badPath, 'not-an-image');

    $post = Post::factory()->create([
        'image_path' => $badPath,
        'image_disk' => 'public',
    ]);

    artisan(MoveImagesToCloudflareImagesCommand::class)->assertSuccessful();

    expect(Storage::disk('cloudflare-images')->exists($badPath))->toBeTrue();
    expect(Storage::disk('public')->exists($badPath))->toBeTrue();
    expect($post->fresh()->image_disk)->toBe('cloudflare-images');
});
