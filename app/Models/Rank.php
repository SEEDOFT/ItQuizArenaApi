<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'required_xp', 'icon'])]
class Rank extends Model
{
    protected function casts(): array
    {
        return [
            'required_xp' => 'integer',
        ];
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('required_xp', 'asc');
    }

    public function scopeHighestForXp($query, int $xp)
    {
        return $query->where('required_xp', '<=', $xp)
            ->orderBy('required_xp', 'desc')
            ->limit(1);
    }
}
