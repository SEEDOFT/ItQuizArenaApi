<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = storage_path('app/private/data/database.json');

        if (! file_exists($jsonPath)) {
            $this->command->error("Database JSON file not found at: {$jsonPath}");

            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        if (! $data || ! isset($data['sections'])) {
            $this->command->error('Invalid database JSON structure');

            return;
        }

        $sectionScoreMap = [
            'Easy' => Course::BEGINNER_SCORE,
            'Medium' => Course::INTERMEDIATE_SCORE,
            'Hard' => Course::ADVANCED_SCORE,
        ];

        $sectionDifficultyMap = [
            'Easy' => 'Beginner',
            'Medium' => 'Intermediate',
            'Hard' => 'Advanced',
        ];

        $letterToIndex = [
            'a' => 0,
            'b' => 1,
            'c' => 2,
            'd' => 3,
        ];

        $count = 0;

        foreach ($data['sections'] as $section) {
            $sectionName = $section['section'];
            $points = $sectionScoreMap[$sectionName] ?? 10;
            $difficulty = $sectionDifficultyMap[$sectionName] ?? $sectionName;

            foreach ($section['questions'] as $q) {
                if (! isset($q['correct_answer_key'], $letterToIndex[$q['correct_answer_key']])) {
                    continue;
                }

                $options = [];
                foreach (['a', 'b', 'c', 'd'] as $letter) {
                    $options[] = $q['options'][$letter] ?? '';
                }

                Question::create([
                    'course_id' => Course::DATABASE,
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

        $this->command->info("Seeded {$count} database questions from JSON.");
    }
}
