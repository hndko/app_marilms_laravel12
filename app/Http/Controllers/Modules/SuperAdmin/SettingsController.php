<?php

namespace App\Http\Controllers\Modules\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\SystemSetting;
use App\Models\Central\ActivityLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('modules.superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.value' => 'nullable|string',
        ]);

        foreach ($request->settings as $key => $data) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $data['value'] ?? '']);
            }
        }

        ActivityLog::log('update_settings', 'Pengaturan sistem diperbarui', 'superadmin', auth()->id());

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
