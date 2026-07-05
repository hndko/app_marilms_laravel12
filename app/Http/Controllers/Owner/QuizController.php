<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index($tenant)
    {
        return view('owner.quizzes.index', compact('tenant'));
    }

    public function create($tenant)
    {
        return view('owner.quizzes.create', compact('tenant'));
    }

    public function store(Request $request, $tenant)
    {
        // Will be implemented in Phase 5
        return redirect()->back();
    }

    public function show($tenant, $quiz)
    {
        return view('owner.quizzes.show', compact('tenant', 'quiz'));
    }

    public function edit($tenant, $quiz)
    {
        return view('owner.quizzes.edit', compact('tenant', 'quiz'));
    }

    public function update(Request $request, $tenant, $quiz)
    {
        return redirect()->back();
    }

    public function destroy($tenant, $quiz)
    {
        return redirect()->back();
    }

    public function generate(Request $request, $tenant)
    {
        // Will be implemented in Phase 4 (LLM Integration)
        return redirect()->back();
    }
}
