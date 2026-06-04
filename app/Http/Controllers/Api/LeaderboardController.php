<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaderboardResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = min((int) $request->get('limit', 10), 100);

        $users = User::orderBy('xp', 'desc')
            ->orderBy('level', 'desc')
            ->take($limit)
            ->get();

        return $this->success(LeaderboardResource::collection($users));
    }
}
