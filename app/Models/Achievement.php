<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['title', 'description', 'key', 'required_value', 'icon'])]
class Achievement extends Model
{
    protected function casts(): array
    {
        return [
            'required_value' => 'integer',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot(['progress', 'unlocked_at'])
            ->withTimestamps();
    }
}
