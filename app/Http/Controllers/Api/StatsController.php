<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return $this->success(new StatsResource($request->user()->loadCount(['quizSessions'])));
    }
}
