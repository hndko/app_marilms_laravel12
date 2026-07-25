<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditGateway implements PaymentGatewayContract
{
    protected PaymentGatewayConfig $config;
    protected string $secretKey;
    protected string $callbackToken;
    protected bool $isProduction;

    public function __construct()
    {
        $this->config = PaymentGatewayConfig::where('gateway', 'xendit')->firstOrFail();
        $this->secretKey = $this->config->getCredential('secret_key', '');
        $this->callbackToken = $this->config->getCredential('callback_token', '');
        $this->isProduction = $this->config->isProduction();
    }

    public function createOrder(TokenOrder $order, array $customerDetails = []): array
    {
        $tenantSlug = $order->owner?->tenant?->slug ?? 'default';
        $finishUrl = route('tenant.owner.tokens', ['tenant' => $tenantSlug, 'status' => 'success']);
        $errorUrl = route('tenant.owner.tokens', ['tenant' => $tenantSlug, 'status' => 'error']);
        $simulateUrl = route('tenant.owner.tokens.simulate', ['tenant' => $tenantSlug, 'order' => $order->id]);

        $payload = [
            'external_id' => $order->id,
            'amount' => (int) $order->amount_idr,
            'description' => 'Paket Token - ' . number_format($order->token_amount) . ' Token',
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => $customerDetails['name'] ?? ($order->owner->name ?? 'Owner'),
                'email' => $customerDetails['email'] ?? ($order->owner->email ?? 'owner@example.com'),
            ],
            'success_redirect_url' => $finishUrl,
            'failure_redirect_url' => $errorUrl,
        ];

        try {
            if (empty($this->secretKey) || str_contains($this->secretKey, 'development_...')) {
                Log::info('XenditGateway: Using simulated Invoice (credentials not configured).', ['order_id' => $order->id]);
                return [
                    'payment_url' => $simulateUrl,
                    'snap_token' => null,
                    'gateway_order_id' => 'XND_SIM_' . time(),
                    'raw_response' => ['simulated' => true, 'message' => 'Simulated Xendit Invoice Response'],
                ];
            }

            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(15)
                ->post("https://api.xendit.co/v2/invoices", $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'payment_url' => $data['invoice_url'] ?? null,
                    'snap_token' => null,
                    'gateway_order_id' => $data['id'] ?? null,
                    'raw_response' => $data,
                ];
            }

            if (!$this->isProduction) {
                return [
                    'payment_url' => $simulateUrl,
                    'snap_token' => null,
                    'gateway_order_id' => 'XND_SIM_' . time(),
                    'raw_response' => ['simulated' => true, 'error' => $response->body()],
                ];
            }

            throw new \Exception('Gagal membuat invoice Xendit: ' . $response->json('message', 'Unknown error'));
        } catch (\Exception $e) {
            if (!$this->isProduction) {
                return [
                    'payment_url' => $simulateUrl,
                    'snap_token' => null,
                    'gateway_order_id' => 'XND_SIM_' . time(),
                    'raw_response' => ['simulated' => true, 'exception' => $e->getMessage()],
                ];
            }
            throw $e;
        }
    }

    public function verifyWebhook(Request $request): bool
    {
        $token = $request->header('x-callback-token');
        
        if (!$this->isProduction && $request->input('simulated_signature') === true) {
            return true;
        }

        if (empty($this->callbackToken)) {
            return !$this->isProduction;
        }

        return hash_equals($this->callbackToken, (string) $token);
    }

    public function parseWebhook(Request $request): array
    {
        $status = strtoupper($request->input('status', ''));
        $normalizedStatus = 'pending';

        if ($status === 'PAID' || $status === 'SETTLED') {
            $normalizedStatus = 'success';
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            $normalizedStatus = 'failed';
        }

        return [
            'order_id' => $request->input('external_id'),
            'gateway_order_id' => $request->input('id'),
            'status' => $normalizedStatus,
            'raw_payload' => $request->all(),
        ];
    }
}
