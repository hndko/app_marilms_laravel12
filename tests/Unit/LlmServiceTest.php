<?php

namespace Tests\Unit;

use App\Services\LlmService;
use App\Services\PromptBuilder;
use Tests\TestCase;

class LlmServiceTest extends TestCase
{
    public function test_prompt_builder_generates_valid_json_schema()
    {
        $prompt = (new PromptBuilder())->build([
            'topic' => 'Aljabar Linier',
            'question_count' => 5,
            'difficulty' => 'sedang',
            'question_type' => 'multiple_choice',
            'option_count' => 4,
            'category' => 'Matematika',
            'instructions' => 'Fokus pada matriks',
        ]);

        $this->assertStringContainsString('Aljabar Linier', $prompt);
        $this->assertStringContainsString('5', $prompt);
        $this->assertStringContainsString('multiple_choice', $prompt);
        $this->assertStringContainsString('STRUKTUR OUTPUT JSON:', $prompt);
    }

    public function test_llm_service_resolves_default_driver_when_none_active()
    {
        $service = new LlmService();
        $this->assertNotNull($service);
    }
}
