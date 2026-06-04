<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'xp' => $this->xp,
            'level' => $this->level,
            'total_quizzes' => $this->total_quizzes,
            'highest_score' => $this->highest_score,
            'best_streak' => $this->best_streak,
            'avatar' => $this->avatar,
            'current_rank' => $this->current_rank?->title,
            'next_rank' => $this->next_rank?->title,
            'next_rank_xp' => $this->next_rank?->required_xp,
            'settings' => new SettingsResource($this->whenLoaded('settings')),
            'created_at' => $this->created_at,
        ];
    }
}
