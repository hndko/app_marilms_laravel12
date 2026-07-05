<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($tenant)
    {
        $user = Auth::guard('participant')->user();

        $quizzes = Quiz::active()
            ->where(function ($query) use ($user) {
                $query->where('is_public', true)
                    ->orWhereHas('participants', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->withCount(['attempts' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get()
            ->map(function ($quiz) use ($user) {
                $lastAttempt = $quiz->attempts()
                    ->where('user_id', $user->id)
                    ->latest()
                    ->first();

                $quiz->last_score = $lastAttempt?->score;
                $quiz->has_passed = $quiz->hasUserPassed($user);
                $quiz->remaining_attempts = $quiz->remainingAttempts($user);
                $quiz->has_active_attempt = $quiz->attempts()
                    ->where('user_id', $user->id)
                    ->inProgress()
                    ->exists();

                return $quiz;
            });

        return view('participant.dashboard', compact('quizzes', 'tenant'));
    }
}
