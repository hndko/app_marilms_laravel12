<?php

namespace App\Jobs;

use App\Models\Central\ActivityLog;
use App\Models\Central\Owner;
use App\Models\Central\SystemSetting;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\Quiz;
use App\Services\LlmService;
use App\Services\PromptBuilder;
use App\Services\TokenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateQuizJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 180; // 3 minutes timeout for LLM generation

    public function __construct(
        public string $tenantId,
        public int|string $ownerId,
        public array $params
    ) {}

    public function handle(
        TokenService $tokenService,
        LlmService $llmService,
        PromptBuilder $promptBuilder
    ): void {
        Log::info("Starting GenerateQuizJob for Tenant [{$this->tenantId}] and Owner [{$this->ownerId}]...");

        // Initialize Tenancy context
        if (!tenancy()->initialized || tenancy()->tenant?->id !== $this->tenantId) {
            tenancy()->initialize($this->tenantId);
        }

        try {
            $owner = Owner::findOrFail($this->ownerId);

            $questionCount = (int) ($this->params['question_count'] ?? 5);
            $tokenPerQuestion = (int) SystemSetting::getValue('token_per_question', 1);
            $requiredTokens = $questionCount * $tokenPerQuestion;

            // 1. Verify balance
            if (!$tokenService->checkBalance($owner, $requiredTokens)) {
                throw new \Exception("Saldo token tidak mencukupi. Dibutuhkan {$requiredTokens} token.");
            }

            // 2. Build Prompt
            $prompt = $promptBuilder->build($this->params);

            // 3. Call LLM Service (with fallback and 3x retry)
            $json = $llmService->generateQuizJson($prompt, $owner->id);

            // 4. Validate output structure
            $promptBuilder->validateStructure($json, $questionCount);

            // 5. Deduct token (atomic transaction in TokenService)
            $tokenService->deduct(
                $owner,
                $requiredTokens,
                'quiz_generate',
                'AI_GEN_' . time(),
                "Generate Kuis AI: " . ($this->params['topic'] ?? 'Topik Kuis') . " ({$questionCount} soal)"
            );

            // 6. Save Quiz and Questions to Tenant Database
            DB::connection('tenant')->transaction(function () use ($json) {
                $quiz = Quiz::create([
                    'title' => $json['title'] ?? ($this->params['topic'] ?? 'Kuis Baru AI'),
                    'description' => $json['description'] ?? "Kuis evaluasi otomatis bertema " . ($this->params['topic'] ?? ''),
                    'category' => $this->params['category'] ?? 'Umum',
                    'time_limit' => (int) ($this->params['time_limit'] ?? 60),
                    'passing_score' => (int) ($this->params['passing_score'] ?? 70),
                    'status' => 'draft',
                    'max_attempts' => (int) ($this->params['max_attempts'] ?? 1),
                ]);

                foreach ($json['questions'] as $idx => $qData) {
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $qData['question_text'],
                        'question_type' => $qData['question_type'] ?? 'multiple_choice',
                        'points' => (int) ($qData['points'] ?? 10),
                        'explanation' => $qData['explanation'] ?? null,
                        'sort_order' => $idx + 1,
                    ]);

                    if (isset($qData['options']) && is_array($qData['options'])) {
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
            });

            Log::info("GenerateQuizJob completed successfully for Tenant [{$this->tenantId}].");

        } catch (\Exception $e) {
            Log::error("GenerateQuizJob failed for Tenant [{$this->tenantId}]: " . $e->getMessage());
            
            ActivityLog::log(
                'llm_generate_failed',
                "Gagal generate kuis AI: " . $e->getMessage(),
                'system',
                $this->ownerId,
                ['tenant' => $this->tenantId, 'error' => $e->getMessage()]
            );

            throw $e;
        } finally {
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }
}
