<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DokuGateway implements PaymentGatewayContract
{
    protected PaymentGatewayConfig $config;

    public function __construct()
    {
        $this->config = PaymentGatewayConfig::where('gateway', 'doku')->firstOrFail();
    }

    public function createOrder(TokenOrder $order, array $customerDetails = []): array
    {
        $tenantSlug = $order->owner?->tenant?->slug ?? 'default';
        $simulateUrl = route('tenant.owner.tokens.simulate', ['tenant' => $tenantSlug, 'order' => $order->id]);

        Log::info('DokuGateway: Using simulated payment (Phase 2 placeholder).', ['order_id' => $order->id]);
        return [
            'payment_url' => $simulateUrl,
            'snap_token' => null,
            'gateway_order_id' => 'DOKU_SIM_' . time(),
            'raw_response' => ['simulated' => true, 'gateway' => 'doku'],
        ];
    }

    public function verifyWebhook(Request $request): bool
    {
        return true;
    }

    public function parseWebhook(Request $request): array
    {
        $status = strtolower($request->input('order.status', $request->input('status', '')));
        $normalized = ($status == 'success' || $status == 'paid') ? 'success' : 'pending';

        return [
            'order_id' => $request->input('order.invoice_number', $request->input('order_id')),
            'gateway_order_id' => $request->input('transaction.status', $request->input('trx_id')),
            'status' => $normalized,
            'raw_payload' => $request->all(),
        ];
    }
}
