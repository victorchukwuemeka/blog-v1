<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'user_id' => User::factory(),
            'parent_id' => null,
            'content' => fake()->paragraph(),
        ];
    }
}
