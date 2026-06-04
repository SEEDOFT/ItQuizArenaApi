<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        Achievement::create([
            'title' => 'First Quiz',
            'description' => 'Complete your first quiz',
            'key' => 'first_quiz',
            'required_value' => 1,
        ]);

        Achievement::create([
            'title' => 'Ten Quizzes',
            'description' => 'Complete 10 quizzes',
            'key' => 'ten_quizzes',
            'required_value' => 10,
        ]);

        Achievement::create([
            'title' => 'Fifty Quizzes',
            'description' => 'Complete 50 quizzes',
            'key' => 'fifty_quizzes',
            'required_value' => 50,
        ]);

        Achievement::create([
            'title' => 'Perfect Score',
            'description' => 'Get a perfect score on a quiz',
            'key' => 'perfect_score',
            'required_value' => 1,
        ]);

        Achievement::create([
            'title' => 'Streak Master',
            'description' => 'Achieve a streak of 10 correct answers',
            'key' => 'streak_master',
            'required_value' => 10,
        ]);
    }
}
