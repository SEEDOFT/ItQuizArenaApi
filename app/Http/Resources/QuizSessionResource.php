<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course' => new CourseResource($this->whenLoaded('course')),
            'score' => $this->score,
            'correct_count' => $this->correct_count,
            'wrong_count' => $this->wrong_count,
            'total_questions' => $this->total_questions,
            'time_spent' => $this->time_spent,
            'streak' => $this->streak,
            'highest_streak' => $this->highest_streak,
            'accuracy' => $this->accuracy,
            'is_perfect' => $this->is_perfect,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
