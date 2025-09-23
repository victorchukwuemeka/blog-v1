<?php

use App\Models\Link;
use App\Models\Post;
use App\Jobs\RecommendPosts;
use App\Actions\CreatePostForLink;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Bus;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\assertDatabaseCount;

it('creates a post for a pending link and soft-deletes previous post', function () {
    $oldPost = Post::factory()->create();
    $link = Link::factory()->withPost()->create(['post_id' => $oldPost->id]);

    $payload = json_encode([
        'title' => 'Sample title',
        'content' => 'Sample content',
        'description' => 'Sample description',
    ]);

    OpenAI::swap(new class($payload)
    {
        public function __construct(private string $outputText) {}

        public function responses()
        {
            $out = $this->outputText;

            return new class($out)
            {
                public function __construct(private string $outputText) {}

                public function create(array $args)
                {
                    return (object) ['outputText' => $this->outputText];
                }
            };
        }
    });
    Bus::fake();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post->user_id)->toBe($link->user_id);
    expect($post->published_at)->toBeNull();

    assertDatabaseHas('links', [
        'id' => $link->id,
        'post_id' => $post->id,
    ]);

    assertSoftDeleted('posts', ['id' => $oldPost->id]);

    Bus::assertDispatched(RecommendPosts::class, function ($job) use ($post) {
        return $job->post->is($post) && $job->afterCommit;
    });
});

it('creates a post for an approved link and uses approval date as published_at', function () {
    $approvedAt = now()->subDay();
    $link = Link::factory()->approved()->create(['is_approved' => $approvedAt]);

    $payload = json_encode([
        'title' => 'Approved title',
        'content' => 'Approved content',
        'description' => 'Approved description',
    ]);

    OpenAI::swap(new class($payload)
    {
        public function __construct(private string $outputText) {}

        public function responses()
        {
            $out = $this->outputText;

            return new class($out)
            {
                public function __construct(private string $outputText) {}

                public function create(array $args)
                {
                    return (object) ['outputText' => $this->outputText];
                }
            };
        }
    });
    Bus::fake();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post->published_at)->not->toBeNull();
    expect($post->published_at->isSameSecond($approvedAt))->toBeTrue();
});

it('rolls back and throws on invalid model output', function () {
    $oldPost = Post::factory()->create();
    $link = Link::factory()->withPost()->create(['post_id' => $oldPost->id]);

    $payload = 'not-json';

    OpenAI::swap(new class($payload)
    {
        public function __construct(private string $outputText) {}

        public function responses()
        {
            $out = $this->outputText;

            return new class($out)
            {
                public function __construct(private string $outputText) {}

                public function create(array $args)
                {
                    return (object) ['outputText' => $this->outputText];
                }
            };
        }
    });
    Bus::fake();

    expect(fn () => app(CreatePostForLink::class)->create($link))
        ->toThrow(RuntimeException::class);

    // No new posts created and link unchanged
    assertDatabaseCount('posts', 1);
    assertDatabaseHas('links', [
        'id' => $link->id,
        'post_id' => $oldPost->id,
    ]);

    Bus::assertNotDispatched(RecommendPosts::class);
});
