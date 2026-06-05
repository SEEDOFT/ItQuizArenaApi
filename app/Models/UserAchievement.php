<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('user_achievements', key: 'id', keyType: 'int')]
#[Fillable(['user_id', 'achievement_id', 'progress', 'unlocked_at'])]
class UserAchievement extends Model
{
    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'unlocked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function getIsUnlockedAttribute(): bool
    {
        return ! is_null($this->unlocked_at);
    }
}
