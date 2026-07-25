<?php

namespace App\Http\Controllers\Modules\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\ActivityLog;
use App\Models\Central\Owner;
use App\Models\Central\TokenOrder;
use App\Models\Central\TokenTransaction;
use App\Models\Tenant\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'bulan_ini');
        $cacheKey = "dashboard_superadmin_{$period}";

        $dashboardData = Cache::remember($cacheKey, 180, function () use ($period) {
            $dateRange = $this->getDateRange($period);
            $prevDateRange = $this->getPreviousDateRange($period);

            // 1. Metrics & Growth Comparison
            $totalOwners = Owner::count();
            $prevOwners = Owner::when($dateRange['start'], fn($q) => $q->where('created_at', '<', $dateRange['start']))->count();
            $ownerGrowth = $this->calculateGrowth($totalOwners, $prevOwners);

            $activeOwners = Owner::where('status', 'active')->count();

            $revenueQuery = TokenOrder::where('status', 'success')
                ->when($dateRange['start'], fn($q) => $q->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]));
            $totalRevenue = (clone $revenueQuery)->sum('amount_idr');

            $prevRevenueQuery = TokenOrder::where('status', 'success')
                ->when($prevDateRange['start'], fn($q) => $q->whereBetween('created_at', [$prevDateRange['start'], $prevDateRange['end']]));
            $prevRevenue = (clone $prevRevenueQuery)->sum('amount_idr');
            $revenueGrowth = $this->calculateGrowth($totalRevenue, $prevRevenue);

            $totalQuizzes = Quiz::count();
            $totalTokensSold = TokenTransaction::where('source', 'purchase')->sum('amount');
            $totalTokensConsumed = TokenTransaction::where('source', 'quiz_generate')->sum('amount');

            // 2. Order Status Distribution
            $orderStatusCounts = [
                'success' => TokenOrder::where('status', 'success')->count(),
                'pending' => TokenOrder::where('status', 'pending')->count(),
                'failed' => TokenOrder::where('status', 'failed')->count(),
                'expired' => TokenOrder::where('status', 'expired')->count(),
            ];

            // 3. Time Series Chart Data (Last 7 Days)
            $chartDays = collect(range(6, 0))->map(function ($daysAgo) {
                $date = Carbon::now()->subDays($daysAgo)->toDateString();
                $rev = TokenOrder::where('status', 'success')->whereDate('created_at', $date)->sum('amount_idr');
                $orders = TokenOrder::whereDate('created_at', $date)->count();
                return [
                    'label' => Carbon::now()->subDays($daysAgo)->format('d M'),
                    'revenue' => $rev,
                    'orders' => $orders,
                ];
            });

            // 4. Recent Orders Table with Eager Loading (prevent N+1)
            $recentOrders = TokenOrder::with(['owner', 'package'])
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            $recentActivities = ActivityLog::orderBy('created_at', 'desc')->take(6)->get();

            return [
                'stats' => [
                    'total_owners' => $totalOwners,
                    'owner_growth' => $ownerGrowth,
                    'active_owners' => $activeOwners,
                    'total_revenue' => $totalRevenue,
                    'revenue_growth' => $revenueGrowth,
                    'total_quizzes' => $totalQuizzes,
                    'total_tokens_sold' => $totalTokensSold,
                    'total_tokens_consumed' => $totalTokensConsumed,
                ],
                'status_distribution' => $orderStatusCounts,
                'chart' => [
                    'labels' => $chartDays->pluck('label'),
                    'revenues' => $chartDays->pluck('revenue'),
                    'orders' => $chartDays->pluck('orders'),
                ],
                'recent_orders' => $recentOrders,
                'recent_activities' => $recentActivities,
            ];
        });

        return view('modules.superadmin.dashboard', [
            'data' => $dashboardData,
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

    private function calculateGrowth(float|int $current, float|int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }
}
