<?php

use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\RankController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::get('/leaderboard', [LeaderboardController::class, 'index']);
Route::get('/ranks', [RankController::class, 'index']);
Route::get('/achievements', [AchievementController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/courses/{course}/questions', [QuestionController::class, 'index']);

    Route::post('/quiz/start', [QuizController::class, 'start']);
    Route::post('/quiz/{session}/answer', [QuizController::class, 'answer']);
    Route::post('/quiz/{session}/finish', [QuizController::class, 'finish']);
    Route::get('/quiz/{session}', [QuizController::class, 'show']);

    Route::get('/user/stats', [StatsController::class, 'show']);
    Route::get('/user/achievements', [AchievementController::class, 'userProgress']);

    Route::get('/user/settings', [SettingsController::class, 'show']);
    Route::put('/user/settings', [SettingsController::class, 'update']);
    Route::patch('/user/settings', [SettingsController::class, 'update']);

    Route::post('/user/reset-progress', [UserController::class, 'resetProgress']);
});
