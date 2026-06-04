<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sound_enabled' => 'sometimes|boolean',
            'music_enabled' => 'sometimes|boolean',
            'show_explanation' => 'sometimes|boolean',
            'question_count' => 'sometimes|integer|min:5|max:50',
            'time_per_question' => 'sometimes|integer|min:10|max:60',
            'theme_mode' => 'sometimes|in:system,dark,light',
            'difficulty' => 'sometimes|in:Beginner,Intermediate,Advanced',
        ];
    }
}
