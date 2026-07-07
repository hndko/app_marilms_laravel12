<?php

namespace App\Contracts;

interface LlmProviderContract
{
    /**
     * Send a prompt to the LLM and return the generated text response.
     *
     * @param string $prompt The prompt text to send to the LLM.
     * @param array $options Additional options (model, max_tokens, temperature, etc.).
     * @return string The raw text or JSON string returned by the LLM.
     * @throws \Exception If generation fails or times out.
     */
    public function generate(string $prompt, array $options = []): string;

    /**
     * Test the connection and API key validity for this provider.
     *
     * @return bool True if connection succeeds, false otherwise.
     */
    public function testConnection(): bool;

    /**
     * Get the identifier name of the provider driver.
     */
    public function getName(): string;
}
