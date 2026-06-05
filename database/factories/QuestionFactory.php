<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        $options = [
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
        ];

        return [
            'course_id' => Course::factory(),
            'question_text' => fake()->sentence().'?',
            'options' => $options,
            'correct_answer' => fake()->numberBetween(0, 3),
            'explanation' => fake()->paragraph(),
            'points' => fake()->randomElement([10, 20, 30, 50]),
            'difficulty' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
        ];
    }

    public function beginner(): static
    {
        return $this->state(fn (array $attrs) => ['difficulty' => 'Beginner']);
    }

    public function intermediate(): static
    {
        return $this->state(fn (array $attrs) => ['difficulty' => 'Intermediate']);
    }

    public function advanced(): static
    {
        return $this->state(fn (array $attrs) => ['difficulty' => 'Advanced']);
    }
}
