<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index($tenant) { return view('owner.reports.index', compact('tenant')); }
    public function quizReport($tenant, $quiz) { return view('owner.reports.quiz', compact('tenant', 'quiz')); }
    public function export($tenant, $type) { return redirect()->back(); }
}
