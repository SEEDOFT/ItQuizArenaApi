<?php

namespace App\Services;

use App\Models\Rank;
use App\Models\User;

class RankingService
{
    public function getCurrentRank(User $user): ?Rank
    {
        return Rank::where('required_xp', '<=', $user->xp)
            ->orderBy('required_xp', 'desc')
            ->first();
    }

    public function getNextRank(User $user): ?Rank
    {
        return Rank::where('required_xp', '>', $user->xp)
            ->orderBy('required_xp', 'asc')
            ->first();
    }

    public function getLeaderboard(int $limit = 10)
    {
        return User::orderBy('xp', 'desc')
            ->orderBy('level', 'desc')
            ->take($limit)
            ->get();
    }
}
