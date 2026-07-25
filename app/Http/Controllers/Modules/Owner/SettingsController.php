<?php

namespace App\Http\Controllers\Modules\Owner;

use App\Http\Controllers\Controller;
use App\Models\Central\SystemSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected function getTenant()
    {
        return tenant('slug') ?? tenant('id') ?? request()->segment(1);
    }

    /**
     * Display tenant settings page.
     */
    public function index()
    {
        $tenant = $this->getTenant();
        
        $settings = [
            'tenant_name' => SystemSetting::getValue('tenant_name', strtoupper($tenant)),
            'tenant_description' => SystemSetting::getValue('tenant_description', 'Portal Ujian & Evaluasi Pembelajaran'),
            'wa_gateway_driver' => SystemSetting::getValue('wa_gateway_driver', 'log'),
            'wa_gateway_key' => SystemSetting::getValue('wa_gateway_key', ''),
            'wa_gateway_endpoint' => SystemSetting::getValue('wa_gateway_endpoint', ''),
            'enable_wa_notification' => SystemSetting::getValue('enable_wa_notification', '1'),
            'enable_email_notification' => SystemSetting::getValue('enable_email_notification', '0'),
            'strict_anti_cheat' => SystemSetting::getValue('strict_anti_cheat', '1'),
            'tab_switch_action' => SystemSetting::getValue('tab_switch_action', 'end_quiz'),
        ];

        return view('modules.owner.settings.index', compact('tenant', 'settings'));
    }

    /**
     * Update tenant settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'tenant_name' => 'required|string|max:100',
            'wa_gateway_driver' => 'required|in:log,fonnte,wablast',
        ]);

        $keys = [
            'tenant_name',
            'tenant_description',
            'wa_gateway_driver',
            'wa_gateway_key',
            'wa_gateway_endpoint',
            'enable_wa_notification',
            'enable_email_notification',
            'strict_anti_cheat',
            'tab_switch_action',
        ];

        foreach ($keys as $key) {
            $value = $request->has($key) ? (string) $request->input($key) : '0';
            SystemSetting::setValue($key, $value);
        }

        // Handle Test Notification if requested
        if ($request->boolean('test_wa') && $request->filled('test_phone')) {
            $phone = $request->input('test_phone');
            $owner = Auth::guard('owner')->user();
            
            $notifService = new NotificationService();
            $testObj = (object) [
                'phone' => $phone,
                'name' => $owner?->name ?: 'Administrator',
            ];

            $sent = $notifService->send('test', $testObj, [], true, false);
            if ($sent) {
                return redirect()->back()->with('success', "Pengaturan disimpan & pesan tes WA berhasil dikirim ke {$phone}!");
            } else {
                return redirect()->back()->with('error', "Pengaturan disimpan, TETAPI gagal mengirim pesan tes ke {$phone}. Periksa API Key / konfigurasi gateway Anda.");
            }
        }

        return redirect()->back()->with('success', 'Konfigurasi sistem tenant berhasil diperbarui!');
    }
}
