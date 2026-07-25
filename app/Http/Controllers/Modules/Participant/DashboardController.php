<?php

namespace App\Http\Controllers\Modules\Participant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request, $tenant)
    {
        $tenantId = tenant('id') ?? 'default';
        $user = Auth::guard('participant')->user();
        $period = $request->get('period', 'semua');

        $cacheKey = "dashboard_participant_{$tenantId}_{$user->id}_{$period}";

        $dashboardData = Cache::remember($cacheKey, 120, function () use ($tenantId, $user, $period) {
            $dateRange = $this->getDateRange($period);

            $attemptsQuery = QuizAttempt::where('quiz_attempts.tenant_id', $tenantId)
                ->where('quiz_attempts.user_id', $user->id)
                ->when($dateRange['start'], fn($q) => $q->whereBetween('quiz_attempts.created_at', [$dateRange['start'], $dateRange['end']]));

            $totalCompleted = (clone $attemptsQuery)->whereIn('quiz_attempts.status', ['submitted', 'timeout', 'force_ended'])->count();
            $avgScore = round((clone $attemptsQuery)->whereNotNull('quiz_attempts.score')->avg('quiz_attempts.score') ?? 0, 1);
            $flaggedCount = (clone $attemptsQuery)->where('quiz_attempts.is_flagged', true)->count();

            // Passed Count (Qualified join columns to avoid ambiguous column 1052 error)
            $passedCount = (clone $attemptsQuery)
                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                ->whereRaw('quiz_attempts.score >= quizzes.passing_score')
                ->count();

            $failedCount = max(0, $totalCompleted - $passedCount);

            // Time series line chart of scores (Last 8 attempts)
            $recentScores = (clone $attemptsQuery)
                ->whereNotNull('quiz_attempts.score')
                ->with('quiz')
                ->orderBy('quiz_attempts.created_at', 'asc')
                ->take(8)
                ->get()
                ->map(fn($att) => [
                    'label' => Carbon::parse($att->created_at)->format('d M'),
                    'score' => $att->score,
                    'quiz_title' => $att->quiz?->title ?? 'Kuis',
                ]);

            // Recent attempts
            $recentAttempts = (clone $attemptsQuery)
                ->with('quiz')
                ->orderBy('quiz_attempts.created_at', 'desc')
                ->take(6)
                ->get();

            return [
                'stats' => [
                    'total_completed' => $totalCompleted,
                    'avg_score' => $avgScore,
                    'passed_count' => $passedCount,
                    'failed_count' => $failedCount,
                    'flagged_count' => $flaggedCount,
                ],
                'chart' => [
                    'labels' => $recentScores->pluck('label'),
                    'scores' => $recentScores->pluck('score'),
                    'quizzes' => $recentScores->pluck('quiz_title'),
                ],
                'recent_attempts' => $recentAttempts,
            ];
        });

        // Fetch active quizzes for participant
        $quizzes = Quiz::active()
            ->where('quizzes.tenant_id', $tenantId)
            ->where(function ($query) use ($user) {
                $query->where('quizzes.is_public', true)
                    ->orWhereHas('participants', function ($q) use ($user) {
                        $q->where('tenant_users.id', $user->id);
                    });
            })
            ->withCount(['attempts' => function ($query) use ($user) {
                $query->where('quiz_attempts.user_id', $user->id);
            }])
            ->get()
            ->map(function ($quiz) use ($user) {
                $lastAttempt = $quiz->attempts()
                    ->where('quiz_attempts.user_id', $user->id)
                    ->latest()
                    ->first();

                $quiz->last_score = $lastAttempt?->score;
                $quiz->has_passed = $quiz->hasUserPassed($user);
                $quiz->remaining_attempts = $quiz->remainingAttempts($user);
                $quiz->has_active_attempt = $quiz->attempts()
                    ->where('quiz_attempts.user_id', $user->id)
                    ->inProgress()
                    ->exists();

                return $quiz;
            });

        return view('modules.participant.dashboard', [
            'data' => $dashboardData,
            'quizzes' => $quizzes,
            'tenant' => $tenant,
            'period' => $period,
        ]);
    }

    private function getDateRange(string $period): array
    {
        return match ($period) {
            '7_hari' => ['start' => Carbon::now()->subDays(7), 'end' => Carbon::now()],
            'bulan_ini' => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()],
            'tahun_ini' => ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()],
            default => ['start' => null, 'end' => null],
        };
    }
}
