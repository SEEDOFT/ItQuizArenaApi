<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAnswerFactory extends Factory
{
    protected $model = QuizAnswer::class;

    public function definition(): array
    {
        return [
            'quiz_session_id' => QuizSession::factory(),
            'question_id' => Question::factory(),
            'selected_option' => fake()->numberBetween(0, 3),
            'is_correct' => fake()->boolean(70),
            'time_spent' => fake()->numberBetween(5, 60),
        ];
    }

    public function correct(): static
    {
        return $this->state(fn (array $attrs) => [
            'is_correct' => true,
        ]);
    }

    public function wrong(): static
    {
        return $this->state(fn (array $attrs) => [
            'is_correct' => false,
        ]);
    }
}
