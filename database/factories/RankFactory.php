<?php

namespace Database\Factories;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;

class RankFactory extends Factory
{
    protected $model = Rank::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->word(),
            'required_xp' => fake()->unique()->numberBetween(0, 5000),
            'icon' => fake()->randomElement(['shield', 'sword', 'crown', 'star']),
        ];
    }
}
