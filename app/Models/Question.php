<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['course_id', 'question_text', 'options', 'correct_answer', 'explanation', 'points', 'difficulty'])]
class Question extends Model
{
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correct_answer' => 'integer',
            'points' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quizAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function scopeWithoutAnswer($query)
    {
        return $query;
    }
}
