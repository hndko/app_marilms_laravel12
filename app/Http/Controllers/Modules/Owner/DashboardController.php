<?php

namespace App\Http\Controllers\Modules\Owner;

use App\Http\Controllers\Controller;
use App\Models\Central\Owner;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\User;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = tenant('slug') ?? tenant('id') ?? request()->segment(1);
        $owner = Auth::guard('owner')->user();

        $stats = [
            'total_quizzes' => Quiz::count(),
            'active_quizzes' => Quiz::where('status', 'active')->count(),
            'total_participants' => User::count(),
            'total_attempts_today' => QuizAttempt::whereDate('created_at', today())->count(),
            'total_attempts_month' => QuizAttempt::whereMonth('created_at', now()->month)->count(),
            'token_balance' => $owner->getTokenBalanceAmount(),
            'is_unlimited' => $owner->isUnlimited(),
        ];

        return view('modules.owner.dashboard', compact('stats', 'owner', 'tenant'));
    }
}
