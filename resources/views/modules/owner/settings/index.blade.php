@extends('layouts.app-backend')

@section('title', 'Pengaturan Tenant')
@section('page-title', 'Konfigurasi Sistem Tenant')

@section('breadcrumb')
    <span>Pengaturan</span>
@endsection

@section('content')
<form method="POST" action="{{ route('tenant.owner.settings.update', ['tenant' => $tenant]) }}" style="display: flex; flex-direction: column; gap: 28px; max-width: 900px; margin: 0 auto;">
    @csrf
    @method('PUT')

    <!-- Section 1: Tenant Profile -->
    <div class="card" style="border-color: rgba(99,102,241,0.4); box-shadow: 0 0 30px rgba(99,102,241,0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(6,182,212,0.1));">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: white;">Profil & Identitas Tenant</h3>
                    <p style="font-size: 13px; color: var(--text-muted);">Atur nama lembaga atau sekolah yang akan ditampilkan kepada seluruh peserta ujian.</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lembaga / Portal <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="tenant_name" class="form-input" required value="{{ old('tenant_name', $settings['tenant_name']) }}" placeholder="Contoh: SMA Negeri 1 / Bimbingan Belajar Sukses" style="font-size: 15px; padding: 12px;">
                </div>
                <div class="form-group">
                    <label class="form-label">Subdomain Tenant (Read-Only)</label>
                    <input type="text" class="form-input" disabled value="{{ strtoupper($tenant) }}" style="background: rgba(0,0,0,0.3); color: var(--text-muted); font-weight: 800;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Deskripsi / Slogan Singkat</label>
                <input type="text" name="tenant_description" class="form-input" value="{{ old('tenant_description', $settings['tenant_description']) }}" placeholder="Portal Ujian & Evaluasi Pembelajaran Berbasis AI">
            </div>
        </div>
    </div>

    <!-- Section 2: WhatsApp Gateway & Notification Config -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fab fa-whatsapp" style="color: var(--success); margin-right: 8px; font-size: 20px;"></i> Konfigurasi WhatsApp Gateway & Notifikasi</h3>
        </div>

        <div class="card-body" style="display: flex; flex-direction: column; gap: 20px;">
            
            <div style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.3); border-radius: 12px; padding: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <strong style="font-size: 14px; color: white; display: block;"><i class="fas fa-bell"></i> Status Pengiriman Notifikasi Otomatis</strong>
                    <span style="font-size: 12px; color: var(--text-muted);">Kirim pesan sambutan, undangan kuis, dan hasil nilai kepada peserta secara otomatis.</span>
                </div>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="enable_wa_notification" value="1" {{ $settings['enable_wa_notification'] == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--success);">
                        <span style="font-weight: 700; color: var(--success); font-size: 13px;">WhatsApp (Aktif)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="enable_email_notification" value="1" {{ $settings['enable_email_notification'] == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                        <span style="font-weight: 700; color: var(--primary-light); font-size: 13px;">Email (Aktif)</span>
                    </label>
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Provider WA Gateway <span style="color: var(--danger);">*</span></label>
                    <select name="wa_gateway_driver" class="form-select" required id="wa-driver-select" onchange="toggleWaFields()">
                        <option value="log" {{ $settings['wa_gateway_driver'] === 'log' ? 'selected' : '' }}>Log / Simulasi (Untuk Development)</option>
                        <option value="fonnte" {{ $settings['wa_gateway_driver'] === 'fonnte' ? 'selected' : '' }}>Fonnte API (Official / Non-Official)</option>
                        <option value="wablast" {{ $settings['wa_gateway_driver'] === 'wablast' ? 'selected' : '' }}>Wablast / Custom Gateway</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">API Key / Token Gateway</label>
                    <input type="password" name="wa_gateway_key" class="form-input" value="{{ old('wa_gateway_key', $settings['wa_gateway_key']) }}" placeholder="Masukkan API Key dari Fonnte / Wablast">
                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Kosongkan jika menggunakan mode Log / Simulasi</p>
                </div>
            </div>

            <div class="form-group" id="endpoint-group" style="display: {{ $settings['wa_gateway_driver'] === 'wablast' ? 'block' : 'none' }}; margin-bottom: 0;">
                <label class="form-label">Endpoint URL (Khusus Wablast / Custom Gateway)</label>
                <input type="url" name="wa_gateway_endpoint" class="form-input" value="{{ old('wa_gateway_endpoint', $settings['wa_gateway_endpoint']) }}" placeholder="https://api.wablast.com/send-message">
            </div>

            <!-- Test Box -->
            <div style="background: var(--bg-input); border: 1px dashed var(--border); border-radius: 12px; padding: 16px; margin-top: 6px;">
                <label class="form-label" style="font-size: 12px; color: var(--accent-light);"><i class="fas fa-vial"></i> Uji Coba Pengiriman Pesan WhatsApp</label>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <input type="text" name="test_phone" class="form-input" placeholder="Masukkan nomor HP Anda (Contoh: 081234567890)" style="flex: 1; min-width: 240px; height: 42px;">
                    <button type="submit" name="test_wa" value="1" class="btn btn-secondary" style="height: 42px; color: var(--success); font-weight: 700;">
                        <i class="fas fa-paper-plane"></i> Simpan & Kirim Pesan Tes
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Section 3: Anti-Cheat & Security Policy -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-shield-alt" style="color: var(--warning); margin-right: 8px;"></i> Kebijakan Anti-Cheat & Keamanan Ujian</h3>
        </div>
        
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Sistem Anti-Cheat <span style="color: var(--danger);">*</span></label>
                    <select name="strict_anti_cheat" class="form-select" required>
                        <option value="1" {{ $settings['strict_anti_cheat'] == '1' ? 'selected' : '' }}>Aktif (Pantau Aktivitas Tab & Window)</option>
                        <option value="0" {{ $settings['strict_anti_cheat'] == '0' ? 'selected' : '' }}>Nonaktif (Bebas / Tanpa Pemantauan)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tindakan Pelanggaran Tab Switch <span style="color: var(--danger);">*</span></label>
                    <select name="tab_switch_action" class="form-select" required>
                        <option value="end_quiz" {{ $settings['tab_switch_action'] === 'end_quiz' ? 'selected' : '' }}>Kumpulkan Paksa Ujian secara Otomatis</option>
                        <option value="warning_only" {{ $settings['tab_switch_action'] === 'warning_only' ? 'selected' : '' }}>Hanya Berikan Peringatan & Catat di Log</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-footer" style="justify-content: flex-end; gap: 12px; background: rgba(0,0,0,0.15);">
            <button type="reset" class="btn btn-ghost">Reset Perubahan</button>
            <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 15px; background: linear-gradient(135deg, var(--primary), var(--accent)); font-weight: 800; box-shadow: 0 10px 25px rgba(99,102,241,0.4);">
                <i class="fas fa-save"></i> Simpan Semua Konfigurasi
            </button>
        </div>
    </div>

</form>

<script>
function toggleWaFields() {
    const driver = document.getElementById('wa-driver-select').value;
    const endpointGroup = document.getElementById('endpoint-group');
    if (endpointGroup) {
        endpointGroup.style.display = driver === 'wablast' ? 'block' : 'none';
    }
}
</script>
@endsection
