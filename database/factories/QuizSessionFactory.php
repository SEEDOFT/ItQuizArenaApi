<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizSessionFactory extends Factory
{
    protected $model = QuizSession::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'score' => fake()->numberBetween(0, 500),
            'correct_count' => fake()->numberBetween(0, 10),
            'wrong_count' => fake()->numberBetween(0, 10),
            'total_questions' => 10,
            'time_spent' => fake()->numberBetween(30, 300),
            'streak' => fake()->numberBetween(0, 5),
            'highest_streak' => fake()->numberBetween(0, 5),
            'status' => 'completed',
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'in_progress']);
    }

    public function perfect(): static
    {
        return $this->state(fn (array $attrs) => [
            'correct_count' => 10,
            'wrong_count' => 0,
            'score' => 500,
            'total_questions' => 10,
        ]);
    }
}
