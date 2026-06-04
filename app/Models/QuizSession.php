<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'course_id',
    'score',
    'correct_count',
    'wrong_count',
    'total_questions',
    'time_spent',
    'streak',
    'highest_streak',
    'status',
])]
class QuizSession extends Model
{
    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'correct_count' => 'integer',
            'wrong_count' => 'integer',
            'total_questions' => 'integer',
            'time_spent' => 'integer',
            'streak' => 'integer',
            'highest_streak' => 'integer',
            'status' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function getAccuracyAttribute(): float
    {
        if ($this->total_questions === 0) {
            return 0;
        }

        return round(($this->correct_count / $this->total_questions) * 100, 1);
    }

    public function getIsPerfectAttribute(): bool
    {
        return $this->correct_count === $this->total_questions && $this->total_questions > 0;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
}
