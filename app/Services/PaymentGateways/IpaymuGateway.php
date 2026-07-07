<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IpaymuGateway implements PaymentGatewayContract
{
    protected PaymentGatewayConfig $config;

    public function __construct()
    {
        $this->config = PaymentGatewayConfig::where('gateway', 'ipaymu')->firstOrFail();
    }

    public function createOrder(TokenOrder $order, array $customerDetails = []): array
    {
        $tenantSlug = $order->owner?->tenant?->slug ?? 'default';
        $simulateUrl = route('tenant.owner.tokens.simulate', ['tenant' => $tenantSlug, 'order' => $order->id]);

        Log::info('IpaymuGateway: Using simulated payment (Phase 2 placeholder).', ['order_id' => $order->id]);
        return [
            'payment_url' => $simulateUrl,
            'snap_token' => null,
            'gateway_order_id' => 'IPAYMU_SIM_' . time(),
            'raw_response' => ['simulated' => true, 'gateway' => 'ipaymu'],
        ];
    }

    public function verifyWebhook(Request $request): bool
    {
        return true;
    }

    public function parseWebhook(Request $request): array
    {
        $status = $request->input('status', '');
        $normalized = ($status == 'berhasil' || $status == '1' || $status == 'success') ? 'success' : 'pending';

        return [
            'order_id' => $request->input('reference_id', $request->input('order_id')),
            'gateway_order_id' => $request->input('trx_id'),
            'status' => $normalized,
            'raw_payload' => $request->all(),
        ];
    }
}
