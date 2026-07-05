<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\ActivityLog;
use App\Models\Central\Owner;
use App\Models\Central\TokenOrder;
use App\Models\Central\TokenTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_owners' => Owner::count(),
            'active_owners' => Owner::where('status', 'active')->count(),
            'inactive_owners' => Owner::where('status', 'inactive')->count(),
            'total_tokens_sold' => TokenTransaction::where('source', 'purchase')->sum('amount'),
            'total_tokens_consumed' => TokenTransaction::where('source', 'quiz_generate')->sum('amount'),
            'total_revenue' => TokenOrder::where('status', 'success')->sum('amount_idr'),
        ];

        $recentActivities = ActivityLog::orderBy('created_at', 'desc')->take(10)->get();

        return view('superadmin.dashboard', compact('stats', 'recentActivities'));
    }
}
