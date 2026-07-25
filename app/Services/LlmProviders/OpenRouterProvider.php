<?php

namespace App\Services\LlmProviders;

use App\Contracts\LlmProviderContract;
use App\Models\Central\LlmProvider as LlmProviderModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterProvider implements LlmProviderContract
{
    public function __construct(protected LlmProviderModel $config) {}

    protected function getApiKey(): string
    {
        $key = trim((string) $this->config->api_key);
        if (!empty($key)) {
            return $key;
        }

        return trim((string) env('OPENROUTER_API_KEY', ''));
    }

    public function generate(string $prompt, array $options = []): string
    {
        $apiKey = $this->getApiKey();
        if (empty($apiKey)) {
            throw new \Exception("API Key untuk OpenRouter belum dikonfigurasi. Harap isi API Key pada menu SuperAdmin > LLM Provider atau di file .env (OPENROUTER_API_KEY).");
        }

        $baseUrl = rtrim($this->config->base_url ?: 'https://openrouter.ai/api/v1', '/');
        $url = $baseUrl . '/chat/completions';

        $model = $options['model'] ?? $this->config->model ?: 'openai/gpt-4o-mini';
        $temperature = (float) ($options['temperature'] ?? $this->config->temperature ?: 0.7);
        $maxTokens = (int) ($options['max_tokens'] ?? $this->config->max_tokens ?: 4000);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'HTTP-Referer' => config('app.url', 'http://localhost'),
            'X-Title' => 'MariLMS AI Generator',
            'Content-Type' => 'application/json',
        ])
        ->timeout(120)
        ->post($url, [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert educational AI quiz generator. You must respond strictly with valid JSON conforming to the requested schema without any markdown code block formatting or extra commentary.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'response_format' => ['type' => 'json_object'],
        ]);

        if ($response->failed()) {
            Log::error("OpenRouter API Error [{$response->status()}]: " . $response->body());
            throw new \Exception("OpenRouter API request failed with status {$response->status()}: " . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';

        if (empty($content)) {
            throw new \Exception('OpenRouter returned empty content.');
        }

        return $content;
    }

    public function testConnection(): bool
    {
        try {
            $apiKey = $this->getApiKey();
            if (empty($apiKey)) {
                return false;
            }

            $baseUrl = rtrim($this->config->base_url ?: 'https://openrouter.ai/api/v1', '/');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])
            ->timeout(10)
            ->get($baseUrl . '/models');

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('OpenRouter connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getName(): string
    {
        return 'openrouter';
    }
}
