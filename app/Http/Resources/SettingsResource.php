<?php

namespace App\Http\Resources;

use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserSettings
 */
class SettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'sound_enabled' => $this->sound_enabled,
            'music_enabled' => $this->music_enabled,
            'show_explanation' => $this->show_explanation,
            'question_count' => $this->question_count,
            'time_per_question' => $this->time_per_question,
            'theme_mode' => $this->theme_mode,
            'difficulty' => $this->difficulty,
        ];
    }
}
