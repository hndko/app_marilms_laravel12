<?php

namespace App\Services\LlmProviders;

use App\Contracts\LlmProviderContract;
use App\Models\Central\LlmProvider as LlmProviderModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomProvider implements LlmProviderContract
{
    public function __construct(protected LlmProviderModel $config) {}

    public function generate(string $prompt, array $options = []): string
    {
        $baseUrl = rtrim($this->config->base_url ?: 'https://api.openai.com/v1', '/');
        $url = str_ends_with($baseUrl, '/chat/completions') ? $baseUrl : $baseUrl . '/chat/completions';

        $model = $options['model'] ?? $this->config->model ?: 'gpt-4o-mini';
        $temperature = (float) ($options['temperature'] ?? $this->config->temperature ?: 0.7);
        $maxTokens = (int) ($options['max_tokens'] ?? $this->config->max_tokens ?: 4000);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config->api_key,
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
            Log::error("Custom LLM API Error [{$response->status()}]: " . $response->body());
            throw new \Exception("Custom LLM API request failed with status {$response->status()}: " . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';

        if (empty($content)) {
            throw new \Exception('Custom LLM returned empty content.');
        }

        return $content;
    }

    public function testConnection(): bool
    {
        try {
            $baseUrl = rtrim($this->config->base_url ?: 'https://api.openai.com/v1', '/');
            $url = str_replace('/chat/completions', '', $baseUrl) . '/models';
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config->api_key,
            ])
            ->timeout(10)
            ->get($url);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('Custom LLM connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getName(): string
    {
        return 'custom';
    }
}
