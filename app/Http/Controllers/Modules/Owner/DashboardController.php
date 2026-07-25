<?php

namespace App\Http\Controllers\Modules\Owner;

use App\Http\Controllers\Controller;
use App\Models\Central\Owner;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\User;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = tenant('id') ?? 'default';
        $tenantSlug = tenant('slug') ?? request()->segment(1) ?? 'default';
        $owner = Auth::guard('owner')->user();
        $period = $request->get('period', 'bulan_ini');

        $cacheKey = "dashboard_owner_{$tenantId}_{$period}";

        $dashboardData = Cache::remember($cacheKey, 180, function () use ($tenantId, $period, $owner) {
            $dateRange = $this->getDateRange($period);
            $prevDateRange = $this->getPreviousDateRange($period);

            // 1. Metrics & Comparison
            $totalQuizzes = Quiz::where('quizzes.tenant_id', $tenantId)->count();
            $prevQuizzes = Quiz::where('quizzes.tenant_id', $tenantId)
                ->where('quizzes.created_at', '<', $dateRange['start'])->count();
            $quizGrowth = $this->calculateGrowth($totalQuizzes, $prevQuizzes);

            $activeParticipants = User::where('tenant_users.tenant_id', $tenantId)->count();
            $prevParticipants = User::where('tenant_users.tenant_id', $tenantId)
                ->where('tenant_users.created_at', '<', $dateRange['start'])->count();
            $participantGrowth = $this->calculateGrowth($activeParticipants, $prevParticipants);

            $attemptsQuery = QuizAttempt::where('quiz_attempts.tenant_id', $tenantId)
                ->when($dateRange['start'], fn($q) => $q->whereBetween('quiz_attempts.created_at', [$dateRange['start'], $dateRange['end']]));
            $totalAttempts = (clone $attemptsQuery)->count();

            $prevAttemptsQuery = QuizAttempt::where('quiz_attempts.tenant_id', $tenantId)
                ->when($prevDateRange['start'], fn($q) => $q->whereBetween('quiz_attempts.created_at', [$prevDateRange['start'], $prevDateRange['end']]));
            $prevAttempts = (clone $prevAttemptsQuery)->count();
            $attemptGrowth = $this->calculateGrowth($totalAttempts, $prevAttempts);

            $avgScore = round((clone $attemptsQuery)->whereNotNull('quiz_attempts.score')->avg('quiz_attempts.score') ?? 0, 1);
            $flaggedCount = (clone $attemptsQuery)->where('quiz_attempts.is_flagged', true)->count();
            $flagRate = $totalAttempts > 0 ? round(($flaggedCount / $totalAttempts) * 100, 1) : 0;

            // 2. Status & Pass/Fail Distribution
            $statusCounts = [
                'submitted' => (clone $attemptsQuery)->where('quiz_attempts.status', 'submitted')->count(),
                'in_progress' => (clone $attemptsQuery)->where('quiz_attempts.status', 'in_progress')->count(),
                'timeout' => (clone $attemptsQuery)->where('quiz_attempts.status', 'timeout')->count(),
                'force_ended' => (clone $attemptsQuery)->where('quiz_attempts.status', 'force_ended')->count(),
            ];

            // Passed vs Failed (Qualified join columns to avoid ambiguous column 1052 error)
            $passedAttempts = (clone $attemptsQuery)
                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                ->whereRaw('quiz_attempts.score >= quizzes.passing_score')
                ->count();
            $failedAttempts = max(0, $totalAttempts - $passedAttempts);

            // 3. Time Series Chart Data (Last 7 Days)
            $chartDays = collect(range(6, 0))->map(function ($daysAgo) use ($tenantId) {
                $date = Carbon::now()->subDays($daysAgo)->toDateString();
                $count = QuizAttempt::where('quiz_attempts.tenant_id', $tenantId)->whereDate('quiz_attempts.created_at', $date)->count();
                $avg = round(QuizAttempt::where('quiz_attempts.tenant_id', $tenantId)->whereDate('quiz_attempts.created_at', $date)->avg('quiz_attempts.score') ?? 0, 1);
                return [
                    'label' => Carbon::now()->subDays($daysAgo)->format('d M'),
                    'attempts' => $count,
                    'avg_score' => $avg,
                ];
            });

            // 4. Recent Attempts Table with Eager Loading
            $recentAttempts = QuizAttempt::where('quiz_attempts.tenant_id', $tenantId)
                ->with(['user', 'quiz'])
                ->orderBy('quiz_attempts.created_at', 'desc')
                ->take(8)
                ->get();

            return [
                'stats' => [
                    'total_quizzes' => $totalQuizzes,
                    'quiz_growth' => $quizGrowth,
                    'active_quizzes' => Quiz::where('quizzes.tenant_id', $tenantId)->where('quizzes.status', 'active')->count(),
                    'total_participants' => $activeParticipants,
                    'participant_growth' => $participantGrowth,
                    'total_attempts' => $totalAttempts,
                    'attempt_growth' => $attemptGrowth,
                    'avg_score' => $avgScore,
                    'flagged_count' => $flaggedCount,
                    'flag_rate' => $flagRate,
                    'token_balance' => $owner?->getTokenBalanceAmount() ?? 0,
                    'is_unlimited' => $owner?->isUnlimited() ?? false,
                ],
                'status_distribution' => $statusCounts,
                'pass_fail_distribution' => [
                    'passed' => $passedAttempts,
                    'failed' => $failedAttempts,
                ],
                'chart' => [
                    'labels' => $chartDays->pluck('label'),
                    'attempts' => $chartDays->pluck('attempts'),
                    'scores' => $chartDays->pluck('avg_score'),
                ],
                'recent_attempts' => $recentAttempts,
            ];
        });

        return view('modules.owner.dashboard', [
            'data' => $dashboardData,
            'owner' => $owner,
            'tenant' => $tenantSlug,
            'period' => $period,
        ]);
    }

    private function getDateRange(string $period): array
    {
        return match ($period) {
            'hari_ini' => ['start' => Carbon::today(), 'end' => Carbon::now()],
            '7_hari' => ['start' => Carbon::now()->subDays(7), 'end' => Carbon::now()],
            'bulan_ini' => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()],
            'tahun_ini' => ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()],
            default => ['start' => null, 'end' => null],
        };
    }

    private function getPreviousDateRange(string $period): array
    {
        return match ($period) {
            'hari_ini' => ['start' => Carbon::yesterday(), 'end' => Carbon::today()],
            '7_hari' => ['start' => Carbon::now()->subDays(14), 'end' => Carbon::now()->subDays(7)],
            'bulan_ini' => ['start' => Carbon::now()->subMonth()->startOfMonth(), 'end' => Carbon::now()->subMonth()->endOfMonth()],
            'tahun_ini' => ['start' => Carbon::now()->subYear()->startOfYear(), 'end' => Carbon::now()->subYear()->endOfYear()],
            default => ['start' => null, 'end' => null],
        };
    }

    private function calculateGrowth(int $current, int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }
}
