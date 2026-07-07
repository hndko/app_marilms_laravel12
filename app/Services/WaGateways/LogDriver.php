<?php

namespace App\Services\WaGateways;

use App\Contracts\WaGatewayContract;
use Illuminate\Support\Facades\Log;

class LogDriver implements WaGatewayContract
{
    public function send(string $phone, string $message): bool
    {
        Log::info("=== [WA LOG DRIVER] ===");
        Log::info("To: {$phone}");
        Log::info("Message: \n{$message}");
        Log::info("=======================");

        return true;
    }

    public function getName(): string
    {
        return 'log';
    }
}
