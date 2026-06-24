<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('courses', key: 'id', keyType: 'int')]
#[Fillable(['id', 'title', 'description', 'category', 'difficulty', 'question_count', 'thumbnail', 'is_active'])]
class Course extends Model
{
    use HasFactory;

    public const int PROGRAMMING = 1;

    public const int NETWORKING = 2;

    public const int DATABASE = 3;

    public const int BEGINNER_SCORE = 10;

    public const int INTERMEDIATE_SCORE = 20;

    public const int ADVANCED_SCORE = 30;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'question_count' => 'integer',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
