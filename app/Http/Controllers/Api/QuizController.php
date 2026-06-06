<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use App\Http\Requests\StartQuizRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuestionWithAnswerResource;
use App\Http\Resources\QuizSessionResource;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use App\Services\AchievementService;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService,
        protected AchievementService $achievementService,
    ) {}

    public function start(StartQuizRequest $request): JsonResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        $course = Course::findOrFail($validatedData['course_id']);
        $count = $validatedData['question_count'] ?? min(10, $course->questions()->count());

        $difficulty = $validatedData['difficulty'] ?? $user->settings?->difficulty;

        $questions = $this->quizService->selectQuestions($course, $count, $difficulty, $user->id);

        if ($questions->isEmpty()) {
            return $this->error('No questions available for this course', 400);
        }

        $session = QuizSession::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'total_questions' => $questions->count(),
            'status' => 'in_progress',
        ]);

        return $this->success([
            'session' => new QuizSessionResource($session->load('course')),
            'questions' => QuestionResource::collection($questions),
        ], 'Quiz started');
    }

    public function answer(AnswerRequest $request, QuizSession $session): JsonResponse
    {
        $user = $request->user();

        if ($session->user_id !== $user->id) {
            return $this->error('Unauthorized', 403);
        }

        if ($session->status === 'completed') {
            return $this->error('Quiz already completed', 400);
        }

        $alreadyAnswered = QuizAnswer::where('quiz_session_id', $session->id)
            ->where('question_id', $request->question_id)
            ->exists();

        if ($alreadyAnswered) {
            return $this->error('Question already answered', 400);
        }

        $question = Question::findOrFail($request->question_id);

        $result = $this->quizService->gradeAnswer($question, $request->selected_option);

        QuizAnswer::create([
            'quiz_session_id' => $session->id,
            'question_id' => $question->id,
            'selected_option' => $request->selected_option,
            'is_correct' => $result['is_correct'],
            'time_spent' => $request->time_spent,
        ]);

        $this->quizService->updateSession(
            $session,
            $result['is_correct'],
            $result['points_earned'],
            $request->time_spent
        );

        $answeredCount = $session->answers()->count();
        $isLastQuestion = $answeredCount >= $session->total_questions;

        return $this->success([
            'is_correct' => $result['is_correct'],
            'points_earned' => $result['points_earned'],
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'question' => new QuestionWithAnswerResource($question),
            'session' => new QuizSessionResource($session->fresh()),
            'is_last_question' => $isLastQuestion,
        ]);
    }

    public function finish(Request $request, QuizSession $session): JsonResponse
    {
        $user = $request->user();

        if ($session->user_id !== $user->id) {
            return $this->error('Unauthorized', 403);
        }

        if ($session->status === 'completed') {
            return $this->success([
                'session' => new QuizSessionResource($session->load('course')),
            ], 'Quiz already completed');
        }

        $oldLevel = $user->level;

        $this->quizService->endSession($session);

        $user->refresh();
        $newLevel = $user->level;

        $newAchievements = $this->achievementService->checkUnlocks($user, $session);

        return $this->success([
            'session' => new QuizSessionResource($session->load('course')),
            'new_achievements' => $newAchievements,
            'xp_gained' => $session->score,
            'level_up' => $newLevel > $oldLevel,
            'new_level' => $newLevel,
        ], 'Quiz completed');
    }

    public function show(Request $request, QuizSession $session): JsonResponse
    {
        $user = $request->user();

        if ($session->user_id !== $user->id) {
            return $this->error('Unauthorized', 403);
        }

        $session->load(['course', 'answers.question']);

        return $this->success([
            'session' => new QuizSessionResource($session),
            'answers' => $session->answers->map(function ($answer) {
                return [
                    'question' => new QuestionWithAnswerResource($answer->question),
                    'selected_option' => $answer->selected_option,
                    'is_correct' => $answer->is_correct,
                    'time_spent' => $answer->time_spent,
                ];
            }),
        ]);
    }
}
