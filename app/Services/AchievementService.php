<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\QuizSession;
use App\Models\User;
use App\Models\UserAchievement;

class AchievementService
{
    public function checkUnlocks(User $user, QuizSession $session): array
    {
        $newlyUnlocked = [];
        $achievements = Achievement::all();

        foreach ($achievements as $achievement) {
            /** @var UserAchievement $userAchievement */
            $userAchievement = UserAchievement::firstOrCreate(
                ['user_id' => $user->id, 'achievement_id' => $achievement->id],
                ['progress' => 0],
            );

            if ($userAchievement->is_unlocked) {
                continue;
            }

            $progress = match ($achievement->key) {
                'first_quiz' => $user->total_quizzes,
                'ten_quizzes' => $user->total_quizzes,
                'fifty_quizzes' => $user->total_quizzes,
                'perfect_score' => $session->is_perfect ? 1 : 0,
                'streak_master' => $user->best_streak,
                default => 0,
            };

            $userAchievement->progress = $progress;

            if ($progress >= $achievement->required_value) {
                $userAchievement->unlocked_at = now();
                $newlyUnlocked[] = $achievement->key;
            }

            $userAchievement->save();
        }

        return $newlyUnlocked;
    }
}
