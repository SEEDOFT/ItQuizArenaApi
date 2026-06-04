<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'difficulty' => $this->difficulty,
            'question_count' => $this->questions()->count(),
            'thumbnail' => $this->thumbnail,
            'created_at' => $this->created_at,
        ];
    }
}
