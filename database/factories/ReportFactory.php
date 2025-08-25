<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    public function definition() : array
    {
        return [
            'post_id' => Post::factory(),
            'content' => fake()->paragraphs(random_int(5, 10), true),
        ];
    }
}
