<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DuitkuGateway implements PaymentGatewayContract
{
    protected PaymentGatewayConfig $config;

    public function __construct()
    {
        $this->config = PaymentGatewayConfig::where('gateway', 'duitku')->firstOrFail();
    }

    public function createOrder(TokenOrder $order, array $customerDetails = []): array
    {
        $tenantSlug = $order->owner?->tenant?->slug ?? 'default';
        $simulateUrl = route('tenant.owner.tokens.simulate', ['tenant' => $tenantSlug, 'order' => $order->id]);

        Log::info('DuitkuGateway: Using simulated payment (Phase 2 placeholder).', ['order_id' => $order->id]);
        return [
            'payment_url' => $simulateUrl,
            'snap_token' => null,
            'gateway_order_id' => 'DUITKU_SIM_' . time(),
            'raw_response' => ['simulated' => true, 'gateway' => 'duitku'],
        ];
    }

    public function verifyWebhook(Request $request): bool
    {
        return true;
    }

    public function parseWebhook(Request $request): array
    {
        $status = $request->input('resultCode', '');
        $normalized = ($status == '00' || $status == '0') ? 'success' : 'failed';

        return [
            'order_id' => $request->input('merchantOrderId', $request->input('order_id')),
            'gateway_order_id' => $request->input('reference'),
            'status' => $normalized,
            'raw_payload' => $request->all(),
        ];
    }
}
