<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AchievementResource;
use App\Models\Achievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->success(AchievementResource::collection(Achievement::all()));
    }

    public function userProgress(Request $request): JsonResponse
    {
        $user = $request->user();
        $userAchievements = $user->achievements()->withPivot(['progress', 'unlocked_at'])->get();

        return $this->success(AchievementResource::collection($userAchievements));
    }
}
