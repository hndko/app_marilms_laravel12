<?php

namespace App\Http\Controllers\Modules\Owner;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateQuizJob;
use App\Models\Central\SystemSetting;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\Quiz;
use App\Services\LlmService;
use App\Services\PromptBuilder;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    protected function getTenant()
    {
        return tenant('slug') ?? tenant('id') ?? request()->segment(1);
    }

    /**
     * Display a listing of quizzes with filter and search (Phase 5.1).
     */
    public function index(Request $request)
    {
        $tenant = $this->getTenant();
        
        $query = Quiz::withCount('questions', 'participants');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $quizzes = $query->latest()->paginate(12)->withQueryString();
        $categories = Quiz::distinct()->pluck('category')->filter();

        return view('modules.owner.quizzes.index', compact('tenant', 'quizzes', 'categories'));
    }

    /**
     * Show the form for creating/generating a new AI Quiz (Phase 4.5).
     */
    public function create()
    {
        $tenant = $this->getTenant();
        $owner = auth('owner')->user();
        
        $tokenBalance = $owner?->getTokenBalanceAmount() ?? 0;
        $isUnlimited = $owner?->isUnlimited() ?? false;
        $tokenPerQuestion = (int) SystemSetting::getValue('token_per_question', 1);

        return view('modules.owner.quizzes.create', compact('tenant', 'owner', 'tokenBalance', 'isUnlimited', 'tokenPerQuestion'));
    }

    /**
     * Store a manually created quiz.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'time_limit' => 'required|integer|min:5|max:300',
            'passing_score' => 'required|integer|min:10|max:100',
            'status' => 'required|in:draft,active,archived',
        ]);

        $quiz = Quiz::create($request->only([
            'title', 'category', 'description', 'time_limit', 'passing_score', 'status'
        ]) + ['max_attempts' => 1]);

        return redirect()->route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id])
            ->with('success', 'Kuis baru berhasil dibuat! Silakan tambahkan soal.');
    }

    /**
     * Process AI Quiz Generation (Phase 4.3).
     */
    public function generate(
        Request $request,
        TokenService $tokenService,
        LlmService $llmService,
        PromptBuilder $promptBuilder
    ) {
        $request->validate([
            'topic' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'difficulty' => 'required|in:mudah,sedang,sulit,campuran',
            'question_count' => 'required|integer|min:1|max:30',
            'option_count' => 'required|integer|min:2|max:5',
            'question_type' => 'required|in:multiple_choice,true_false',
            'time_limit' => 'required|integer|min:5|max:300',
            'passing_score' => 'required|integer|min:10|max:100',
            'instructions' => 'nullable|string|max:1000',
        ]);

        $owner = auth('owner')->user();
        if (!$owner) {
            return redirect()->back()->with('error', 'Sesi login tidak valid.');
        }

        $questionCount = (int) $request->question_count;
        $tokenPerQuestion = (int) SystemSetting::getValue('token_per_question', 1);
        $requiredTokens = $questionCount * $tokenPerQuestion;

        if (!$tokenService->checkBalance($owner, $requiredTokens)) {
            return redirect()->back()->withInput()
                ->with('error', "Saldo token tidak cukup. Dibutuhkan {$requiredTokens} token untuk meng-generate {$questionCount} soal.");
        }

        try {
            $tenantId = tenancy()->tenant?->id ?? $this->getTenant();
            
            // Execute generation synchronously for instant feedback
            $job = new GenerateQuizJob($tenantId, $owner->id, $request->all());
            $job->handle($tokenService, $llmService, $promptBuilder);

            return redirect()->route('tenant.owner.quizzes.index')
                ->with('success', "Berhasil meng-generate {$questionCount} soal kuis AI bertema '{$request->topic}'!");
        } catch (\Exception $e) {
            Log::error('Quiz Generation error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', "Gagal meng-generate soal kuis: " . $e->getMessage());
        }
    }

    /**
     * Display the specified quiz preview (Phase 5.2).
     */
    public function show($quizId)
    {
        $tenant = $this->getTenant();
        $quiz = Quiz::with(['questions' => fn($q) => $q->orderBy('sort_order')->with(['options' => fn($o) => $o->orderBy('sort_order')])])
            ->withCount('participants', 'attempts')
            ->findOrFail($quizId);

        return view('modules.owner.quizzes.show', compact('tenant', 'quiz'));
    }

    /**
     * Show the form for editing the specified quiz and its questions (Phase 5.2 & 5.3).
     */
    public function edit($quizId)
    {
        $tenant = $this->getTenant();
        $quiz = Quiz::with(['questions' => fn($q) => $q->orderBy('sort_order')->with(['options' => fn($o) => $o->orderBy('sort_order')])])
            ->findOrFail($quizId);

        return view('modules.owner.quizzes.edit', compact('tenant', 'quiz'));
    }

    /**
     * Update the specified quiz and save modified questions/options (Phase 5.2 & 5.3).
     */
    public function update(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'time_limit' => 'required|integer|min:5|max:300',
            'passing_score' => 'required|integer|min:10|max:100',
            'status' => 'required|in:draft,active,archived',
            'max_attempts' => 'required|integer|min:1|max:10',
        ]);

        DB::transaction(function () use ($request, $quiz) {
            $quiz->update($request->only([
                'title', 'category', 'description', 'time_limit', 'passing_score', 'status', 'max_attempts'
            ]));

            // Update questions if provided in request
            if ($request->has('questions') && is_array($request->questions)) {
                $existingQIds = [];

                foreach ($request->questions as $idx => $qData) {
                    if (!empty($qData['id']) && $qData['id'] !== 'new') {
                        $question = Question::where('quiz_id', $quiz->id)->find($qData['id']);
                        if ($question) {
                            $question->update([
                                'question_text' => $qData['question_text'],
                                'question_type' => $qData['question_type'] ?? 'multiple_choice',
                                'points' => (int) ($qData['points'] ?? 10),
                                'explanation' => $qData['explanation'] ?? null,
                                'sort_order' => $idx + 1,
                            ]);
                            $existingQIds[] = $question->id;
                        }
                    } else {
                        $question = Question::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $qData['question_text'],
                            'question_type' => $qData['question_type'] ?? 'multiple_choice',
                            'points' => (int) ($qData['points'] ?? 10),
                            'explanation' => $qData['explanation'] ?? null,
                            'sort_order' => $idx + 1,
                        ]);
                        $existingQIds[] = $question->id;
                    }

                    // Update options
                    if (isset($qData['options']) && is_array($qData['options'])) {
                        $question->options()->delete(); // Re-create options cleanly
                        foreach ($qData['options'] as $optIdx => $optData) {
                            QuestionOption::create([
                                'question_id' => $question->id,
                                'option_text' => $optData['option_text'],
                                'is_correct' => !empty($optData['is_correct']),
                                'sort_order' => $optIdx + 1,
                            ]);
                        }
                    }
                }

                // Remove deleted questions
                Question::where('quiz_id', $quiz->id)->whereNotIn('id', $existingQIds)->delete();
            }
        });

        return redirect()->route('tenant.owner.quizzes.show', ['quiz' => $quiz->id])
            ->with('success', 'Kuis dan butir soal berhasil diperbarui!');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();

        return redirect()->route('tenant.owner.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus!');
    }
}
