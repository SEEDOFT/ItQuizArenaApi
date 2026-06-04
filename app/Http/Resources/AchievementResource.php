<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'key' => $this->key,
            'required_value' => $this->required_value,
            'icon' => $this->icon,
            'progress' => $this->pivot?->progress ?? 0,
            'is_unlocked' => ! is_null($this->pivot?->unlocked_at),
            'unlocked_at' => $this->pivot?->unlocked_at,
        ];
    }
}
