<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => ucfirst(fake()->word()),
            'slug' => fake()->slug(),
            'content' => fake()->paragraphs(random_int(1, 3), true),
        ];
    }
}
