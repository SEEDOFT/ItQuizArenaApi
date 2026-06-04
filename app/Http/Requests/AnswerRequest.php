<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => 'required|integer|exists:questions,id',
            'selected_option' => 'required|integer|min:-1|max:3',
            'time_spent' => 'required|integer|min:0|max:300',
        ];
    }
}
