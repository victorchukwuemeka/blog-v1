<?php

namespace Database\Factories;

use App\Str;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition() : array
    {
        if (! app()->runningUnitTests()) {
            $image = Http::get('https://picsum.photos/1280/720')
                ->throw()
                ->body();

            Storage::disk('public')->put($path = '/images/posts/' . Str::random() . '.jpg', $image);
        } else {
            $path = null;
        }

        return [
            'user_id' => User::factory(),
            'image_path' => $path ?? null,
            'image_disk' => $path ? 'public' : null,
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(random_int(3, 10), true),
            'serp_title' => fake()->sentence(),
            'description' => fake()->sentences(random_int(1, 2), true),
            'canonical_url' => fake()->url(),
            'is_commercial' => fake()->boolean(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'sessions_count' => fake()->numberBetween(0, 1000),
        ];
    }

    public function withCategories(array $categories = []) : self
    {
        return $this->afterCreating(
            fn (Post $post) => $post->categories()->sync(
                ! empty($categories)
                    ? $categories
                    : Category::factory(random_int(1, 3))->create()
            )
        );
    }

    public function withComments(?int $count = null) : self
    {
        return $this->afterCreating(
            fn (Post $post) => Comment::factory($count ?? random_int(1, 10))
                ->create(['post_id' => $post->id])
        );
    }
}
