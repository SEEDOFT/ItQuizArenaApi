<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Table('users', key: 'id', keyType: 'int')]
#[Fillable(['name', 'username', 'email', 'password', 'google_id', 'avatar', 'xp', 'level', 'total_quizzes', 'highest_score', 'best_streak'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $attributes = [
        'xp' => 0,
        'level' => 1,
        'total_quizzes' => 0,
        'highest_score' => 0,
        'best_streak' => 0,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'xp' => 'integer',
            'level' => 'integer',
            'total_quizzes' => 'integer',
            'highest_score' => 'integer',
            'best_streak' => 'integer',
        ];
    }

    public function quizSessions(): HasMany
    {
        return $this->hasMany(QuizSession::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot(['progress', 'unlocked_at'])
            ->withTimestamps();
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function getCurrentRankAttribute(): ?Rank
    {
        return Rank::where('required_xp', '<=', $this->xp)
            ->orderBy('required_xp', 'desc')
            ->first();
    }

    public function getNextRankAttribute(): ?Rank
    {
        return Rank::where('required_xp', '>', $this->xp)
            ->orderBy('required_xp', 'asc')
            ->first();
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->settings()->create([]);
            $achievements = Achievement::all();
            foreach ($achievements as $achievement) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'progress' => 0,
                ]);
            }
        });
    }
}
