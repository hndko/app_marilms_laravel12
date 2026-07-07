<?php

namespace App\Services\WaGateways;

use App\Contracts\WaGatewayContract;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablastDriver implements WaGatewayContract
{
    protected string $apiKey;
    protected string $endpoint;

    public function __construct(string $apiKey = '', string $endpoint = '')
    {
        $this->apiKey = $apiKey ?: config('services.wablast.key', '');
        $this->endpoint = $endpoint ?: config('services.wablast.endpoint', 'https://api.wablast.com/send-message');
    }

    public function send(string $phone, string $message): bool
    {
        if (empty($this->apiKey) || empty($this->endpoint)) {
            Log::warning('Wablast API Key or Endpoint is missing. Message not sent.', ['phone' => $phone]);
            return false;
        }

        $formattedPhone = $this->formatPhone($phone);

        try {
            $response = Http::timeout(10)->post($this->endpoint, [
                'token' => $this->apiKey,
                'phone' => $formattedPhone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WA message sent via Wablast to {$formattedPhone}");
                return true;
            }

            Log::error("Wablast API Error: " . $response->body(), ['phone' => $formattedPhone]);
            return false;
        } catch (\Exception $e) {
            Log::error("Wablast Exception: " . $e->getMessage(), ['phone' => $formattedPhone]);
            return false;
        }
    }

    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }

    public function getName(): string
    {
        return 'wablast';
    }
}
