<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Central\ActivityLog;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\TokenOrder;
use App\Models\Central\TokenPackage;
use App\Models\Central\TokenTransaction;
use App\Services\PaymentGatewayManager;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    public function __construct(
        protected PaymentGatewayManager $gatewayManager,
        protected TokenService $tokenService
    ) {}

    /**
     * Display token dashboard: current balance, purchase options, and transaction history.
     */
    public function index(Request $request)
    {
        $tenant = tenant('slug') ?? tenant('id') ?? request()->segment(1);
        $owner = auth('owner')->user();

        if ($request->status === 'success' || $request->status === 'finish') {
            session()->now('success', 'Pembelian paket token sedang diproses atau telah berhasil!');
        } elseif ($request->status === 'error') {
            session()->now('error', 'Pembelian token dibatalkan atau gagal.');
        }

        $packages = TokenPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price_idr')
            ->get();

        $gateways = PaymentGatewayConfig::where('is_active', true)->get();
        if ($gateways->isEmpty()) {
            // Fallback to all configured gateways for development and sandbox testing
            $gateways = PaymentGatewayConfig::all();
        }

        $transactions = TokenTransaction::where('owner_id', $owner?->id)
            ->latest()
            ->paginate(15);

        return view('owner.tokens.index', compact('tenant', 'owner', 'packages', 'gateways', 'transactions'));
    }

    /**
     * Process a token package purchase request.
     */
    public function purchase(Request $request)
    {
        $tenant = tenant('slug') ?? tenant('id') ?? request()->segment(1);
        $request->validate([
            'package_id' => 'required|exists:token_packages,id',
            'gateway' => 'required|string',
        ]);

        $owner = auth('owner')->user();
        if (!$owner) {
            return redirect()->back()->with('error', 'Sesi login tidak valid.');
        }

        $package = TokenPackage::findOrFail($request->package_id);

        $order = TokenOrder::create([
            'owner_id' => $owner->id,
            'package_id' => $package->id,
            'token_amount' => $package->token_amount,
            'amount_idr' => $package->price_idr,
            'gateway' => strtolower($request->gateway),
            'status' => 'pending',
            'expired_at' => now()->addDay(),
        ]);

        try {
            $driver = $this->gatewayManager->resolve($request->gateway);
            $result = $driver->createOrder($order, [
                'name' => $owner->name,
                'email' => $owner->email,
                'phone' => $owner->phone ?? '08123456789',
            ]);

            if (!empty($result['gateway_order_id'])) {
                $order->update(['gateway_order_id' => $result['gateway_order_id']]);
            }

            if (!empty($result['payment_url'])) {
                return redirect()->to($result['payment_url']);
            }

            return redirect()->route('tenant.owner.tokens', ['tenant' => $tenant])
                ->with('error', 'Gateway tidak mengembalikan URL pembayaran.');
        } catch (\Exception $e) {
            Log::error('Token purchase error: ' . $e->getMessage(), ['order_id' => $order->id]);
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display sandbox/simulation checkout page for testing without external payment APIs.
     */
    public function simulatePayment(Request $request, $orderId)
    {
        $tenant = tenant('slug') ?? tenant('id') ?? request()->segment(1);
        $order = TokenOrder::with('package', 'owner')->findOrFail($orderId);

        return view('owner.tokens.simulate', compact('tenant', 'order'));
    }

    /**
     * Process simulated payment action (Success or Fail).
     */
    public function processSimulation(Request $request, $orderId)
    {
        $tenant = tenant('slug') ?? tenant('id') ?? request()->segment(1);
        $order = TokenOrder::with('package', 'owner')->findOrFail($orderId);
        $action = $request->input('action', 'success');

        if ($action === 'success' && !$order->isSuccess()) {
            $order->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $packageName = $order->package?->name ?? 'Paket Token';
            $this->tokenService->credit(
                $order->owner,
                (int) $order->token_amount,
                'package_purchase',
                (string) $order->id,
                "Pembelian simulasi via {$order->gateway}: {$packageName}"
            );

            ActivityLog::log(
                'token_purchase_success',
                "Owner {$order->owner->name} berhasil membeli {$order->token_amount} token via {$order->gateway} (Simulasi Sandbox)",
                'system',
                null,
                ['order_id' => $order->id, 'gateway' => $order->gateway, 'tokens' => $order->token_amount]
            );

            return redirect()->route('tenant.owner.tokens', ['tenant' => $tenant])
                ->with('success', "Pembelian {$order->token_amount} token berhasil disimulasikan! Saldo Anda telah bertambah.");
        }

        if ($action === 'failed') {
            $order->update(['status' => 'failed']);
            return redirect()->route('tenant.owner.tokens', ['tenant' => $tenant])
                ->with('error', "Pembelian dibatalkan atau gagal dalam simulasi.");
        }

        return redirect()->route('tenant.owner.tokens', ['tenant' => $tenant])
            ->with('info', "Status pesanan tidak berubah.");
    }
}
