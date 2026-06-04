<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function index(Course $course): JsonResponse
    {
        if (! $course->is_active) {
            return $this->notFound('Course not found');
        }

        return $this->success(QuestionResource::collection($course->questions()->get()));
    }
}
