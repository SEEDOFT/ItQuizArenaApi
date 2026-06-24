<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Question;
use Illuminate\Database\Seeder;

class ProgrammingQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = storage_path('app/private/data/programming.json');

        if (! file_exists($jsonPath)) {
            $this->command->error("Programming JSON file not found at: {$jsonPath}");

            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        if (! $data || ! isset($data['sections'])) {
            $this->command->error('Invalid programming JSON structure');

            return;
        }

        $difficultyScores = [
            'Beginner' => Course::BEGINNER_SCORE,
            'Intermediate' => Course::INTERMEDIATE_SCORE,
            'Advanced' => Course::ADVANCED_SCORE,
        ];

        $letterToIndex = [
            'a' => 0,
            'b' => 1,
            'c' => 2,
            'd' => 3,
        ];

        $count = 0;

        foreach ($data['sections'] as $section) {
            $difficulty = $section['section'];
            $points = $difficultyScores[$difficulty] ?? 10;

            foreach ($section['questions'] as $q) {
                if (! isset($q['correct_answer_key'], $letterToIndex[$q['correct_answer_key']])) {
                    continue;
                }

                $options = [];
                foreach (['a', 'b', 'c', 'd'] as $letter) {
                    $options[] = $q['options'][$letter] ?? '';
                }

                Question::create([
                    'course_id' => Course::PROGRAMMING,
                    'question_text' => $q['question'],
                    'options' => $options,
                    'correct_answer' => $letterToIndex[$q['correct_answer_key']],
                    'explanation' => $q['description'] ?? '',
                    'points' => $points,
                    'difficulty' => $difficulty,
                ]);

                $count++;
            }
        }

        $this->command->info("Seeded {$count} programming questions from JSON.");
    }
}
