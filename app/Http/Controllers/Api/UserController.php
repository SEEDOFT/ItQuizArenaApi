<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizSession;
use App\Models\UserAchievement;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function resetProgress(): JsonResponse
    {
        $user = auth()->user();

        QuizSession::where('user_id', $user->id)->delete();
        UserAchievement::where('user_id', $user->id)->update(['progress' => 0, 'unlocked_at' => null]);

        $user->update([
            'xp' => 0,
            'level' => 1,
            'total_quizzes' => 0,
            'highest_score' => 0,
            'best_streak' => 0,
        ]);

        return response()->json([
            'status' => ['code' => '200', 'message' => 'Progress reset successfully', 'success' => true],
            'data' => null,
        ]);
    }
}
