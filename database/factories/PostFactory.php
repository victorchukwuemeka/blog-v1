<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'user_id' => User::factory(),
            'image_path' => null,
            'image_disk' => null,
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(random_int(3, 10), true),
            'description' => fake()->sentences(random_int(1, 2), true),
            'canonical_url' => fake()->url(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
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
