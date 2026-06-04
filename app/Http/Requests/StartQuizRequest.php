<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|integer|exists:courses,id',
            'question_count' => 'sometimes|integer|min:1|max:50',
            'difficulty' => 'sometimes|string|in:Beginner,Intermediate,Advanced',
        ];
    }
}
