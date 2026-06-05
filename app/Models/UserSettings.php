<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('user_settings', key: 'id', keyType: 'int')]
#[Fillable([
    'user_id',
    'sound_enabled',
    'music_enabled',
    'show_explanation',
    'question_count',
    'time_per_question',
    'theme_mode',
    'difficulty',
])]
class UserSettings extends Model
{
    protected function casts(): array
    {
        return [
            'sound_enabled' => 'boolean',
            'music_enabled' => 'boolean',
            'show_explanation' => 'boolean',
            'question_count' => 'integer',
            'time_per_question' => 'integer',
            'theme_mode' => 'string',
            'difficulty' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
