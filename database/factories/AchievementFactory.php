<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'key' => fake()->unique()->slug(1),
            'required_value' => fake()->numberBetween(1, 50),
            'icon' => fake()->randomElement(['trophy', 'star', 'medal', 'fire', 'zap']),
        ];
    }
}
