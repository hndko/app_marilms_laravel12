@extends('layouts.app-backend')

@section('title', 'Gateway')
@section('page-title', 'Konfigurasi Gateway')
@section('breadcrumb') <span>Gateway</span> @endsection

@section('content')
<!-- Tabs -->
<div class="tabs">
    <button class="tab-link active" onclick="showTab('payment', this)">
        <i class="fas fa-credit-card" style="margin-right: 6px;"></i>Payment
    </button>
    <button class="tab-link" onclick="showTab('email', this)">
        <i class="fas fa-envelope" style="margin-right: 6px;"></i>Email
    </button>
    <button class="tab-link" onclick="showTab('whatsapp', this)">
        <i class="fab fa-whatsapp" style="margin-right: 6px;"></i>WhatsApp
    </button>
</div>

<!-- Payment Tab -->
<div id="tab-payment" class="tab-content">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @foreach($paymentGateways as $key => $gw)
        <div class="card">
            <div class="card-header">
                <h3>{{ $gw->display_name }}</h3>
                @if($gw->is_active)
                    <span class="badge badge-success">Aktif</span>
                @else
                    <span class="badge badge-danger">Nonaktif</span>
                @endif
            </div>
            <form method="POST" action="{{ route('superadmin.gateways.payment.update', $key) }}">
                @csrf @method('PUT')
                <div class="card-body">
                    @if($key === 'midtrans')
                        <div class="form-group">
                            <label class="form-label">Server Key</label>
                            <input type="password" name="credentials[server_key]" class="form-input"
                                value="{{ $gw->credentials['server_key'] ?? '' }}" placeholder="SB-Mid-server-...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Client Key</label>
                            <input type="password" name="credentials[client_key]" class="form-input"
                                value="{{ $gw->credentials['client_key'] ?? '' }}" placeholder="SB-Mid-client-...">
                        </div>
                    @elseif($key === 'xendit')
                        <div class="form-group">
                            <label class="form-label">Secret Key</label>
                            <input type="password" name="credentials[secret_key]" class="form-input"
                                value="{{ $gw->credentials['secret_key'] ?? '' }}" placeholder="xnd_development_...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Callback Token</label>
                            <input type="password" name="credentials[callback_token]" class="form-input"
                                value="{{ $gw->credentials['callback_token'] ?? '' }}">
                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label">API Key</label>
                            <input type="password" name="credentials[api_key]" class="form-input"
                                value="{{ $gw->credentials['api_key'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secret Key / Merchant Code</label>
                            <input type="password" name="credentials[secret_key]" class="form-input"
                                value="{{ $gw->credentials['secret_key'] ?? '' }}">
                        </div>
                    @endif

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Mode</label>
                            <select name="mode" class="form-select">
                                <option value="sandbox" {{ $gw->mode === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                <option value="production" {{ $gw->mode === 'production' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $gw->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$gw->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Webhook URL</label>
                        <input type="text" class="form-input" value="{{ url('/webhook/' . $key) }}" readonly
                            style="color: var(--text-muted); cursor: default;">
                        <div class="form-hint">Salin URL ini ke konfigurasi {{ $gw->display_name }}</div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>

<!-- Email Tab -->
<div id="tab-email" class="tab-content" style="display: none;">
    <div class="card" style="max-width: 600px;">
        <div class="card-header"><h3>Konfigurasi Email (SMTP)</h3></div>
        <form method="POST" action="{{ route('superadmin.gateways.email.update') }}">
            @csrf @method('PUT')
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Driver</label>
                        <select name="driver" class="form-select">
                            <option value="smtp" {{ ($emailConfig->driver ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ ($emailConfig->driver ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ ($emailConfig->driver ?? '') === 'ses' ? 'selected' : '' }}>SES</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Encryption</label>
                        <select name="encryption" class="form-select">
                            <option value="tls" {{ ($emailConfig->encryption ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($emailConfig->encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ empty($emailConfig->encryption ?? '') ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Host</label>
                        <input type="text" name="host" class="form-input" value="{{ $emailConfig->host ?? '' }}" placeholder="smtp.gmail.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Port</label>
                        <input type="number" name="port" class="form-input" value="{{ $emailConfig->port ?? 587 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" value="{{ $emailConfig->username ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="{{ $emailConfig ? 'Kosongkan jika tidak diubah' : '' }}">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">From Address</label>
                        <input type="email" name="from_address" class="form-input" value="{{ $emailConfig->from_address ?? '' }}" placeholder="noreply@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">From Name</label>
                        <input type="text" name="from_name" class="form-input" value="{{ $emailConfig->from_name ?? 'MariLMS' }}">
                    </div>
                </div>
            </div>
            <div class="card-footer" style="justify-content: space-between;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('test-email-modal').style.display='flex'">
                    <i class="fas fa-paper-plane"></i> Kirim Test Email
                </button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>

    <!-- Test Email Modal -->
    <div id="test-email-modal" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal" style="max-width: 400px;" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3>Kirim Test Email</h3>
                <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="{{ route('superadmin.gateways.email.test') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Email Tujuan</label>
                        <input type="email" name="to" class="form-input" required placeholder="test@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.modal-backdrop').style.display='none'">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- WhatsApp Tab -->
<div id="tab-whatsapp" class="tab-content" style="display: none;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
        @forelse($whatsappConfigs as $wa)
        <div class="card">
            <div class="card-header">
                <h3>{{ ucfirst($wa->provider) }}</h3>
                @if($wa->is_active)
                    <span class="badge badge-success">Aktif</span>
                @else
                    <span class="badge badge-danger">Nonaktif</span>
                @endif
            </div>
            <form method="POST" action="{{ route('superadmin.gateways.whatsapp.update', $wa->id) }}">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Provider</label>
                        <select name="provider" class="form-select">
                            <option value="fonnte" {{ $wa->provider === 'fonnte' ? 'selected' : '' }}>Fonnte</option>
                            <option value="wablast" {{ $wa->provider === 'wablast' ? 'selected' : '' }}>WaBlast</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">API Key</label>
                        <input type="password" name="api_key" class="form-input" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sender Number</label>
                        <input type="text" name="sender_number" class="form-input" value="{{ $wa->sender_number }}" required>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Aktif</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $wa->is_active ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ !$wa->is_active ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Default</label>
                            <select name="is_default" class="form-select">
                                <option value="1" {{ $wa->is_default ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ !$wa->is_default ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
        @empty
        <div class="card" style="grid-column: 1 / -1;">
            <div class="empty-state">
                <i class="fab fa-whatsapp"></i>
                <h3>Belum ada WhatsApp Gateway</h3>
                <p>WhatsApp gateway akan dikonfigurasi di fase berikutnya.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + name).style.display = 'block';
    btn.classList.add('active');
}
</script>
@endsection
