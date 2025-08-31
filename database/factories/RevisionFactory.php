<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Revision>
 */
class RevisionFactory extends Factory
{
    public function definition() : array
    {
        return [
            'report_id' => Report::factory(),
            'data' => ['foo' => 'bar'],
        ];
    }
}
