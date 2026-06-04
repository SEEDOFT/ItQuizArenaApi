<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['quiz_session_id', 'question_id', 'selected_option', 'is_correct', 'time_spent'])]
class QuizAnswer extends Model
{
    protected function casts(): array
    {
        return [
            'selected_option' => 'integer',
            'is_correct' => 'boolean',
            'time_spent' => 'integer',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(QuizSession::class, 'quiz_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
