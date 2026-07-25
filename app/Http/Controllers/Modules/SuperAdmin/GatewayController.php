<?php

namespace App\Http\Controllers\Modules\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\PaymentGatewayConfig;
use App\Models\Central\EmailGatewayConfig;
use App\Models\Central\WhatsappGatewayConfig;
use App\Models\Central\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGatewayConfig::all()->keyBy('gateway');
        $emailConfig = EmailGatewayConfig::first();
        $whatsappConfigs = WhatsappGatewayConfig::global()->get();

        // Ensure all payment gateways exist
        $gatewayNames = ['midtrans' => 'Midtrans', 'xendit' => 'Xendit', 'ipaymu' => 'iPaymu', 'doku' => 'DOKU', 'duitku' => 'Duitku'];
        foreach ($gatewayNames as $key => $name) {
            if (!isset($paymentGateways[$key])) {
                $paymentGateways[$key] = PaymentGatewayConfig::create([
                    'gateway' => $key,
                    'display_name' => $name,
                    'credentials' => [],
                    'mode' => 'sandbox',
                    'is_active' => false,
                ]);
            }
        }

        return view('modules.superadmin.gateways.index', compact('paymentGateways', 'emailConfig', 'whatsappConfigs'));
    }

    public function updatePayment(Request $request, $gateway)
    {
        $config = PaymentGatewayConfig::where('gateway', $gateway)->firstOrFail();

        $request->validate([
            'credentials' => 'required|array',
            'mode' => 'required|in:sandbox,production',
            'is_active' => 'boolean',
        ]);

        $config->update([
            'credentials' => $request->credentials,
            'mode' => $request->mode,
            'is_active' => $request->boolean('is_active'),
            'webhook_url' => url("/webhook/{$gateway}"),
        ]);

        ActivityLog::log('update_payment_gateway', "Payment gateway diperbarui: {$gateway}", 'superadmin', auth()->id());

        return back()->with('success', ucfirst($gateway) . ' berhasil dikonfigurasi.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'driver' => 'required|string',
            'host' => 'nullable|string',
            'port' => 'nullable|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|string',
            'from_address' => 'nullable|email',
            'from_name' => 'nullable|string',
        ]);

        $config = EmailGatewayConfig::first();
        if ($config) {
            $data = $request->except('password');
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }
            $config->update(array_merge($data, ['is_active' => true]));
        } else {
            EmailGatewayConfig::create(array_merge($request->all(), ['is_active' => true]));
        }

        ActivityLog::log('update_email_gateway', 'Konfigurasi email gateway diperbarui', 'superadmin', auth()->id());

        return back()->with('success', 'Konfigurasi email berhasil disimpan.');
    }

    public function updateWhatsapp(Request $request, $id)
    {
        $request->validate([
            'provider' => 'required|in:fonnte,wablast',
            'api_key' => 'nullable|string',
            'sender_number' => 'required|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $config = WhatsappGatewayConfig::findOrFail($id);
        $data = $request->except('api_key');
        if ($request->filled('api_key')) {
            $data['api_key'] = $request->api_key;
        }
        $config->update(array_merge($data, [
            'is_active' => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
        ]));

        ActivityLog::log('update_wa_gateway', "WhatsApp gateway diperbarui: {$config->provider}", 'superadmin', auth()->id());

        return back()->with('success', 'Konfigurasi WhatsApp berhasil disimpan.');
    }

    public function testEmail(Request $request)
    {
        $request->validate(['to' => 'required|email']);

        try {
            Mail::raw('Ini adalah email percobaan dari MariLMS. Jika Anda menerima ini, konfigurasi email sudah benar.', function ($message) use ($request) {
                $message->to($request->to)->subject('MariLMS — Test Email');
            });

            return back()->with('success', "Email percobaan berhasil dikirim ke {$request->to}.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengirim email: " . $e->getMessage());
        }
    }

    public function testWhatsapp(Request $request, $id)
    {
        return back()->with('info', 'Fitur test WhatsApp akan tersedia setelah driver gateway diimplementasikan.');
    }
}
