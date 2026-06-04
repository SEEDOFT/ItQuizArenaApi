<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Question;
use App\Models\QuizSession;
use Illuminate\Support\Collection;

class QuizService
{
    public function selectQuestions(Course $course, int $count = 10, ?string $difficulty = null): Collection
    {
        $query = $course->questions();

        if ($difficulty !== null) {
            $query->where('difficulty', $difficulty);
        }

        $totalQuestions = $query->count();
        $count = min($count, $totalQuestions);

        $questions = $query->inRandomOrder()->take($count)->get();

        return $questions->isEmpty()
            ? $questions
            : $this->interleaveByDifficulty($questions);
    }

    private function interleaveByDifficulty(Collection $questions): Collection
    {
        $groups = $questions->groupBy(function ($q) {
            return $q->difficulty ?? 'Beginner';
        })->sortKeys();

        $result = collect();
        $order = collect(['Beginner', 'Intermediate', 'Advanced']);

        while ($result->count() < $questions->count()) {
            foreach ($order as $level) {
                $group = $groups->get($level);
                if ($group && $group->isNotEmpty()) {
                    $result->push($group->shift());
                }
            }
        }

        return $result;
    }

    public function gradeAnswer(Question $question, int $selectedOption): array
    {
        $isCorrect = $question->correct_answer === $selectedOption;

        return [
            'is_correct' => $isCorrect,
            'points_earned' => $isCorrect ? $question->points : 0,
        ];
    }

    public function updateSession(QuizSession $session, bool $isCorrect, int $pointsEarned, int $timeSpent): void
    {
        if ($isCorrect) {
            $session->increment('score', $pointsEarned);
            $session->increment('correct_count');
            $session->increment('streak');
        } else {
            $session->increment('wrong_count');
            $session->streak = 0;
        }

        if ($session->streak > $session->highest_streak) {
            $session->highest_streak = $session->streak;
        }

        $session->increment('time_spent', $timeSpent);
        $session->save();
    }

    public function endSession(QuizSession $session): QuizSession
    {
        $session->status = 'completed';
        $session->save();

        $user = $session->user;

        $user->increment('total_quizzes');
        $user->increment('xp', $session->score);

        if ($session->score > $user->highest_score) {
            $user->highest_score = $session->score;
        }

        if ($session->highest_streak > $user->best_streak) {
            $user->best_streak = $session->highest_streak;
        }

        $user->level = (int) floor($user->xp / 500) + 1;
        $user->save();

        $session->load('course');

        return $session;
    }
}
