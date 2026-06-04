<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Course::active();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        return $this->success(CourseResource::collection($query->get()));
    }

    public function show(Course $course): JsonResponse
    {
        if (! $course->is_active) {
            return $this->notFound('Course not found');
        }

        return $this->success(new CourseResource($course));
    }
}
