<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\ActivityLog;
use App\Models\Central\TokenTransaction;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'activity');

        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->paginate(20, ['*'], 'activity_page');

        $tokenLogs = TokenTransaction::with('owner')
            ->latest('created_at')
            ->paginate(20, ['*'], 'token_page');

        return view('superadmin.logs.index', compact('activityLogs', 'tokenLogs', 'tab'));
    }
}
