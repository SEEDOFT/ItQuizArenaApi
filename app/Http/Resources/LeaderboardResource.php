<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'player_name' => $this->name,
            'xp' => $this->xp,
            'level' => $this->level,
            'rank' => $this->current_rank?->title,
            'total_quizzes' => $this->total_quizzes,
        ];
    }
}
