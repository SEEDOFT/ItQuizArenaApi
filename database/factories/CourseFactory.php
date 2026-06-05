<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Programming', 'Networking', 'Database', 'Cyber Security', 'Web Development', 'Cloud & DevOps']),
            'difficulty' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'question_count' => fake()->numberBetween(5, 50),
            'is_active' => true,
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
