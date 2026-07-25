<?php

namespace Database\Seeders;

use App\Models\Central\LlmProvider;
use Illuminate\Database\Seeder;

class LlmProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LlmProvider::firstOrCreate(
            ['name' => 'OpenRouter (Default)'],
            [
                'provider_type' => 'openrouter',
                'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
                'api_key' => env('OPENROUTER_API_KEY', ''),
                'model' => env('OPENROUTER_MODEL', 'openai/gpt-4o-mini'),
                'max_tokens' => 4000,
                'temperature' => 0.7,
                'priority' => 1,
                'status' => 'active',
            ]
        );
    }
}
