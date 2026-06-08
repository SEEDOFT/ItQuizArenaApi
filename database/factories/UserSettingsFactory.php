<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingsFactory extends Factory
{
    protected $model = UserSettings::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'sound_enabled' => fake()->boolean(),
            'music_enabled' => fake()->boolean(),
            'show_explanation' => fake()->boolean(),
            'question_count' => fake()->numberBetween(5, 50),
            'time_per_question' => fake()->randomElement([10, 15, 20, 30, 45, 60]),
            'theme_mode' => fake()->randomElement(['system', 'dark', 'light']),
            'difficulty' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
        ];
    }
}
