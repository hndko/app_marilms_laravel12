<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\TokenOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransGateway implements PaymentGatewayContract
{
    protected PaymentGatewayConfig $config;
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->config = PaymentGatewayConfig::where('gateway', 'midtrans')->firstOrFail();
        $this->serverKey = $this->config->getCredential('server_key', '');
        $this->clientKey = $this->config->getCredential('client_key', '');
        $this->isProduction = $this->config->isProduction();
        $this->baseUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    public function createOrder(TokenOrder $order, array $customerDetails = []): array
    {
        $tenantSlug = $order->owner?->tenant?->slug ?? 'default';
        $finishUrl = route('tenant.owner.tokens', ['tenant' => $tenantSlug, 'status' => 'success']);
        $errorUrl = route('tenant.owner.tokens', ['tenant' => $tenantSlug, 'status' => 'error']);
        $simulateUrl = route('tenant.owner.tokens.simulate', ['tenant' => $tenantSlug, 'order' => $order->id]);

        $payload = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => (int) $order->amount_idr,
            ],
            'customer_details' => [
                'first_name' => $customerDetails['name'] ?? ($order->owner->name ?? 'Owner'),
                'email' => $customerDetails['email'] ?? ($order->owner->email ?? 'owner@marilms.com'),
                'phone' => $customerDetails['phone'] ?? ($order->owner->phone ?? '08123456789'),
            ],
            'item_details' => [
                [
                    'id' => (string) ($order->package_id ?? 'custom'),
                    'price' => (int) $order->amount_idr,
                    'quantity' => 1,
                    'name' => 'Paket Token - ' . number_format($order->token_amount) . ' Token',
                ],
            ],
            'callbacks' => [
                'finish' => $finishUrl,
                'error' => $errorUrl,
            ],
        ];

        try {
            if (empty($this->serverKey) || str_contains($this->serverKey, 'server-...')) {
                Log::info('MidtransGateway: Using simulated Snap token (credentials not configured or test placeholder used).', ['order_id' => $order->id]);
                return [
                    'payment_url' => $simulateUrl,
                    'snap_token' => 'SIMULATED_SNAP_TOKEN_' . $order->id,
                    'gateway_order_id' => 'MIDTRANS_SIM_' . time(),
                    'raw_response' => ['simulated' => true, 'message' => 'Simulated Midtrans Snap Response'],
                ];
            }

            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(15)
                ->post("{$this->baseUrl}/transactions", $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'payment_url' => $data['redirect_url'] ?? null,
                    'snap_token' => $data['token'] ?? null,
                    'gateway_order_id' => null,
                    'raw_response' => $data,
                ];
            }

            Log::error('Midtrans API Error: ' . $response->body(), ['order_id' => $order->id]);
            
            if (!$this->isProduction) {
                return [
                    'payment_url' => $simulateUrl,
                    'snap_token' => 'SIMULATED_SNAP_TOKEN_' . $order->id,
                    'gateway_order_id' => 'MIDTRANS_SIM_' . time(),
                    'raw_response' => ['simulated' => true, 'error' => $response->body()],
                ];
            }

            throw new \Exception('Gagal membuat transaksi Midtrans: ' . $response->json('error_messages.0', 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage(), ['order_id' => $order->id]);
            if (!$this->isProduction) {
                return [
                    'payment_url' => $simulateUrl,
                    'snap_token' => 'SIMULATED_SNAP_TOKEN_' . $order->id,
                    'gateway_order_id' => 'MIDTRANS_SIM_' . time(),
                    'raw_response' => ['simulated' => true, 'exception' => $e->getMessage()],
                ];
            }
            throw $e;
        }
    }

    public function verifyWebhook(Request $request): bool
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        if (!$orderId || !$signatureKey) {
            return false;
        }

        if (!$this->isProduction && $signatureKey === 'simulated_signature_key') {
            return true;
        }

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    public function parseWebhook(Request $request): array
    {
        $transactionStatus = $request->input('transaction_status', '');
        $fraudStatus = $request->input('fraud_status', '');

        $normalizedStatus = 'pending';

        if ($transactionStatus == 'capture') {
            $normalizedStatus = ($fraudStatus == 'challenge') ? 'pending' : 'success';
        } elseif ($transactionStatus == 'settlement') {
            $normalizedStatus = 'success';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $normalizedStatus = 'failed';
        } elseif ($transactionStatus == 'pending') {
            $normalizedStatus = 'pending';
        }

        return [
            'order_id' => $request->input('order_id'),
            'gateway_order_id' => $request->input('transaction_id'),
            'status' => $normalizedStatus,
            'raw_payload' => $request->all(),
        ];
    }
}
