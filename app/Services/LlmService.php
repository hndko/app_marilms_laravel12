<?php

namespace App\Services;

use App\Contracts\LlmProviderContract;
use App\Models\Central\ActivityLog;
use App\Models\Central\LlmProvider as LlmProviderModel;
use App\Services\LlmProviders\CustomProvider;
use App\Services\LlmProviders\DeepSeekProvider;
use App\Services\LlmProviders\OpenRouterProvider;
use Illuminate\Support\Facades\Log;

class LlmService
{
    /**
     * Resolve the driver instance for a given LLM Provider config model.
     */
    public function resolveDriver(LlmProviderModel $config): LlmProviderContract
    {
        return match (strtolower($config->provider_type)) {
            'openrouter' => new OpenRouterProvider($config),
            'deepseek' => new DeepSeekProvider($config),
            default => new CustomProvider($config),
        };
    }

    /**
     * Test connection for a specific provider config.
     */
    public function testConnection(LlmProviderModel $config): bool
    {
        try {
            $driver = $this->resolveDriver($config);
            return $driver->testConnection();
        } catch (\Exception $e) {
            Log::error("LLM Test Connection error ({$config->name}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate quiz structured JSON using active LLM providers with automatic fallback and retry.
     *
     * @param string $prompt The prompt instruction built by PromptBuilder.
     * @param int|string|null $ownerId Optional owner ID to include owner-specific providers.
     * @return array The parsed and validated JSON structure containing quiz and questions.
     * @throws \Exception If all providers and retries fail.
     */
    public function generateQuizJson(string $prompt, $ownerId = null): array
    {
        $providers = LlmProviderModel::whereIn('status', ['active', 'fallback'])
            ->where(function ($query) use ($ownerId) {
                $query->whereNull('owner_id');
                if ($ownerId) {
                    $query->orWhere('owner_id', $ownerId);
                }
            })
            ->orderBy('priority')
            ->get();

        if ($providers->isEmpty()) {
            throw new \Exception('Tidak ada LLM Provider yang aktif atau dikonfigurasi dalam sistem.');
        }

        $lastError = 'Unknown error';

        foreach ($providers as $providerConfig) {
            $driver = $this->resolveDriver($providerConfig);
            $providerName = $providerConfig->name;

            Log::info("Attempting quiz generation using LLM Provider: {$providerName}");

            // Retry up to 3 times per provider if JSON parsing fails
            for ($attempt = 1; $attempt <= 3; $attempt++) {
                try {
                    $rawResponse = $driver->generate($prompt);

                    // Clean up markdown code blocks if the LLM wrapped JSON in ```json ... ```
                    $cleanJson = $this->cleanJsonString($rawResponse);

                    $parsed = json_decode($cleanJson, true);

                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsed)) {
                        throw new \Exception('Invalid JSON format returned by LLM: ' . json_last_error_msg());
                    }

                    // Basic structure validation
                    if (!isset($parsed['questions']) || !is_array($parsed['questions'])) {
                        throw new \Exception('JSON missing required "questions" array.');
                    }

                    Log::info("Successfully generated quiz JSON via {$providerName} on attempt {$attempt}.");

                    // Log activity
                    ActivityLog::log(
                        'llm_generate_success',
                        "Berhasil generate soal AI via {$providerName}",
                        'system',
                        null,
                        ['provider' => $providerName, 'questions_count' => count($parsed['questions'])]
                    );

                    return $parsed;
                } catch (\Exception $e) {
                    $lastError = $e->getMessage();
                    Log::warning("LLM Generation attempt {$attempt} failed on provider [{$providerName}]: {$lastError}");
                    
                    if ($attempt < 3) {
                        // Short sleep before retry
                        usleep(500000); // 500ms
                    }
                }
            }

            Log::warning("Provider [{$providerName}] exhausted all 3 attempts. Falling back to next provider in chain...");
        }

        Log::error("All LLM providers failed to generate quiz JSON. Last error: {$lastError}");
        throw new \Exception("Gagal menghasilkan soal kuis dari semua LLM Provider. Error terakhir: {$lastError}");
    }

    /**
     * Clean markdown code block formatting from raw LLM responses.
     */
    protected function cleanJsonString(string $raw): string
    {
        $trimmed = trim($raw);

        // Remove leading ```json or ``` and trailing ```
        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/is', $trimmed, $matches)) {
            return $matches[1];
        }

        // Find first { and last }
        $firstBrace = strpos($trimmed, '{');
        $lastBrace = strrpos($trimmed, '}');

        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            return substr($trimmed, $firstBrace, $lastBrace - $firstBrace + 1);
        }

        return $trimmed;
    }
}
