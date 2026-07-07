<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Central\ActivityLog;
use App\Models\Central\TokenOrder;
use App\Services\PaymentGatewayManager;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $gatewayManager,
        protected TokenService $tokenService
    ) {}

    public function midtrans(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'midtrans');
    }

    public function xendit(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'xendit');
    }

    public function ipaymu(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'ipaymu');
    }

    public function doku(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'doku');
    }

    public function duitku(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'duitku');
    }

    /**
     * Generic webhook handler for all payment gateways.
     */
    protected function handleWebhook(Request $request, string $gatewayName): JsonResponse
    {
        Log::info("Webhook received from [{$gatewayName}]", $request->all());

        try {
            $driver = $this->gatewayManager->resolve($gatewayName);
        } catch (\Exception $e) {
            Log::error("Webhook Gateway Resolve Error: " . $e->getMessage());
            return response()->json(['error' => 'Unsupported gateway'], 400);
        }

        // Verify HMAC / signature
        if (!$driver->verifyWebhook($request)) {
            Log::warning("Webhook verification failed for [{$gatewayName}]", [
                'ip' => $request->ip(),
                'payload' => $request->all(),
            ]);
            return response()->json(['error' => 'Invalid signature or unauthorized'], 403);
        }

        // Parse webhook payload
        $parsed = $driver->parseWebhook($request);
        $orderId = $parsed['order_id'] ?? null;
        $status = $parsed['status'] ?? 'pending';
        $gatewayOrderId = $parsed['gateway_order_id'] ?? null;

        if (!$orderId) {
            Log::error("Webhook [{$gatewayName}] missing order_id in payload.", $parsed);
            return response()->json(['error' => 'Missing order reference'], 400);
        }

        // Find TokenOrder by ID or gateway_order_id
        $order = TokenOrder::with('owner', 'package')->where('id', $orderId)
            ->orWhere('gateway_order_id', $orderId)
            ->first();

        if (!$order) {
            Log::warning("Webhook [{$gatewayName}] order not found: {$orderId}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Idempotency check: if order is already success, return OK immediately
        if ($order->isSuccess()) {
            Log::info("Webhook [{$gatewayName}] order already settled: {$order->id}");
            return response()->json(['status' => 'ok', 'message' => 'Already processed']);
        }

        // Update order status
        $order->status = $status;
        if ($gatewayOrderId && !$order->gateway_order_id) {
            $order->gateway_order_id = $gatewayOrderId;
        }

        if ($status === 'success') {
            $order->paid_at = now();
            $order->save();

            // Credit tokens to owner using TokenService
            if ($order->owner) {
                $packageName = $order->package?->name ?? 'Paket Token Custom';
                $this->tokenService->credit(
                    $order->owner,
                    (int) $order->token_amount,
                    'package_purchase',
                    (string) $order->id,
                    "Pembelian via {$gatewayName}: {$packageName}"
                );

                ActivityLog::log(
                    'token_purchase_success',
                    "Owner {$order->owner->name} berhasil membeli {$order->token_amount} token via {$gatewayName} (Rp " . number_format($order->amount_idr, 0, ',', '.') . ")",
                    'system',
                    null,
                    ['order_id' => $order->id, 'gateway' => $gatewayName, 'tokens' => $order->token_amount]
                );
            }
        } elseif ($status === 'failed' || $status === 'expired') {
            $order->save();
            Log::info("Webhook [{$gatewayName}] order status updated to {$status}: {$order->id}");
        } else {
            $order->save();
        }

        return response()->json(['status' => 'ok', 'order_status' => $order->status]);
    }
}
