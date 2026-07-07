<?php

namespace App\Services\WaGateways;

use App\Contracts\WaGatewayContract;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteDriver implements WaGatewayContract
{
    protected string $apiKey;
    protected string $endpoint = 'https://api.fonnte.com/send';

    public function __construct(string $apiKey = '')
    {
        $this->apiKey = $apiKey ?: config('services.fonnte.key', '');
    }

    public function send(string $phone, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::warning('Fonnte API Key is missing. Message not sent.', ['phone' => $phone]);
            return false;
        }

        // Format phone number to standard Indonesian format (628xxxx)
        $formattedPhone = $this->formatPhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->timeout(10)->post($this->endpoint, [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful() && ($response->json('status') === true || $response->json('status') === 'true')) {
                Log::info("WA message sent via Fonnte to {$formattedPhone}");
                return true;
            }

            Log::error("Fonnte API Error: " . $response->body(), ['phone' => $formattedPhone]);
            return false;
        } catch (\Exception $e) {
            Log::error("Fonnte Exception: " . $e->getMessage(), ['phone' => $formattedPhone]);
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
        return 'fonnte';
    }
}
