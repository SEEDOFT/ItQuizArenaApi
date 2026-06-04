<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_quizzes' => $this->total_quizzes,
            'total_correct' => $this->quizSessions()->completed()->sum('correct_count'),
            'total_wrong' => $this->quizSessions()->completed()->sum('wrong_count'),
            'highest_score' => $this->highest_score,
            'best_streak' => $this->best_streak,
            'xp' => $this->xp,
            'level' => $this->level,
            'current_rank' => $this->current_rank?->title,
            'overall_accuracy' => $this->when(
                $this->total_quizzes > 0,
                function () {
                    $totalCorrect = $this->quizSessions()->completed()->sum('correct_count');
                    $totalQuestions = $this->quizSessions()->completed()->sum('total_questions');

                    return $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 1) : 0;
                }
            ),
        ];
    }
}
