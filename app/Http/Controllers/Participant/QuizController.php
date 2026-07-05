<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Central\SystemSetting;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAnswer;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Show quiz details before starting.
     */
    public function show($tenant, Quiz $quiz)
    {
        $user = Auth::guard('participant')->user();

        if (!$quiz->canUserTake($user)) {
            return redirect()->route('tenant.participant.dashboard', $tenant)
                ->with('error', 'Anda tidak memiliki akses ke kuis ini.');
        }

        $remainingAttempts = $quiz->remainingAttempts($user);
        $hasPassed = $quiz->hasUserPassed($user);
        $previousAttempts = $quiz->attempts()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('participant.quiz.show', compact('tenant', 'quiz', 'remainingAttempts', 'hasPassed', 'previousAttempts'));
    }

    /**
     * Start a new quiz attempt.
     */
    public function startAttempt(Request $request, $tenant, Quiz $quiz)
    {
        $user = Auth::guard('participant')->user();

        // Validation checks
        if (!$quiz->canUserTake($user)) {
            return back()->with('error', 'Kuis tidak dapat diakses.');
        }

        $remaining = $quiz->remainingAttempts($user);
        if ($remaining !== null && $remaining <= 0) {
            return back()->with('error', 'Anda telah mencapai batas percobaan.');
        }

        // Check for existing in-progress attempt
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->inProgress()
            ->first();

        if ($existingAttempt) {
            // Resume existing attempt
            if ($existingAttempt->isExpired()) {
                $existingAttempt->submit('time_up');
                return redirect()->route('tenant.participant.quiz.attempt.result', [
                    'tenant' => $tenant,
                    'attempt' => $existingAttempt->id,
                ]);
            }

            return redirect()->route('tenant.participant.quiz.attempt.take', [
                'tenant' => $tenant,
                'attempt' => $existingAttempt->id,
            ]);
        }

        // Create new attempt
        $totalDurationSeconds = $quiz->getTotalDurationSeconds();

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'total_duration_seconds' => $totalDurationSeconds,
            'status' => 'in_progress',
        ]);

        return redirect()->route('tenant.participant.quiz.attempt.take', [
            'tenant' => $tenant,
            'attempt' => $attempt->id,
        ]);
    }

    /**
     * Take quiz — main quiz-taking page.
     */
    public function takeQuiz($tenant, QuizAttempt $attempt)
    {
        $user = Auth::guard('participant')->user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        if (!$attempt->isInProgress()) {
            return redirect()->route('tenant.participant.quiz.attempt.result', [
                'tenant' => $tenant,
                'attempt' => $attempt->id,
            ]);
        }

        // Check expiry server-side
        if ($attempt->isExpired()) {
            $attempt->submit('time_up');
            return redirect()->route('tenant.participant.quiz.attempt.result', [
                'tenant' => $tenant,
                'attempt' => $attempt->id,
            ]);
        }

        $quiz = $attempt->quiz;
        $isRetry = $quiz->attempts()
            ->where('user_id', $user->id)
            ->where('id', '!=', $attempt->id)
            ->exists();

        // Load questions — randomize on retry
        $questions = $quiz->questions()->with('options')->get();

        if ($isRetry) {
            $questions = $questions->shuffle();
            $questions->each(function ($question) {
                $question->setRelation('options', $question->options->shuffle());
            });
        }

        // Load existing answers (for refresh tolerance / auto-save)
        $existingAnswers = $attempt->answers->keyBy('question_id');

        $remainingSeconds = $attempt->getRemainingSeconds();

        return view('participant.quiz.take', compact(
            'tenant', 'attempt', 'quiz', 'questions', 'existingAnswers', 'remainingSeconds', 'isRetry'
        ));
    }

    /**
     * Auto-save a single answer.
     */
    public function saveAnswer(Request $request, $tenant, QuizAttempt $attempt)
    {
        $user = Auth::guard('participant')->user();

        if ($attempt->user_id !== $user->id || !$attempt->isInProgress()) {
            return response()->json(['error' => 'Invalid attempt'], 403);
        }

        if ($attempt->isExpired()) {
            $attempt->submit('time_up');
            return response()->json(['expired' => true, 'message' => 'Waktu habis']);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_option_id' => 'required|exists:question_options,id',
        ]);

        $option = \App\Models\Tenant\QuestionOption::find($request->selected_option_id);

        QuizAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'selected_option_id' => $request->selected_option_id,
                'is_correct' => $option->is_correct,
                'answered_at' => now(),
            ]
        );

        return response()->json(['saved' => true]);
    }

    /**
     * Submit quiz attempt.
     */
    public function submitAttempt(Request $request, $tenant, QuizAttempt $attempt)
    {
        $user = Auth::guard('participant')->user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $endReason = $request->input('reason', 'manual');
        $attempt->submit($endReason);

        return redirect()->route('tenant.participant.quiz.attempt.result', [
            'tenant' => $tenant,
            'attempt' => $attempt->id,
        ]);
    }

    /**
     * Show quiz result.
     */
    public function showResult($tenant, QuizAttempt $attempt)
    {
        $user = Auth::guard('participant')->user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $attempt->load(['quiz', 'answers.question.options', 'answers.selectedOption']);
        $quiz = $attempt->quiz;
        $hasPassed = $attempt->hasPassed();
        $remainingAttempts = $quiz->remainingAttempts($user);

        return view('participant.quiz.result', compact(
            'tenant', 'attempt', 'quiz', 'hasPassed', 'remainingAttempts'
        ));
    }

    /**
     * Get remaining time from server (timer sync endpoint).
     */
    public function getRemainingTime($tenant, QuizAttempt $attempt)
    {
        $user = Auth::guard('participant')->user();

        if ($attempt->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($attempt->isExpired() && $attempt->isInProgress()) {
            $attempt->submit('time_up');
            return response()->json(['remaining_seconds' => 0, 'expired' => true]);
        }

        return response()->json([
            'remaining_seconds' => $attempt->getRemainingSeconds(),
            'expired' => false,
        ]);
    }

    /**
     * Quiz history for participant.
     */
    public function history($tenant)
    {
        $user = Auth::guard('participant')->user();

        $attempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz')
            ->latest()
            ->paginate(20);

        return view('participant.history', compact('tenant', 'attempts'));
    }
}
