@extends('layouts.app-backend')

@section('title', 'Token & Top-up')
@section('page-title', 'Token & Pembelian')

@section('breadcrumb')
    <span>Token & Top-up</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 32px;">

    <!-- Token Balance Overview Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(6,182,212,0.15)); border: 1px solid rgba(99,102,241,0.3); padding: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, var(--warning), #d97706); display: flex; align-items: center; justify-content: center; font-size: 28px; color: white; box-shadow: 0 8px 20px rgba(245,158,11,0.3);">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <span style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: var(--text-secondary);">Saldo Token Anda Saat Ini</span>
                    <h2 style="font-size: 32px; font-weight: 800; color: var(--text-white); margin-top: 4px;">
                        @if($owner?->isUnlimited())
                            <span style="color: var(--accent-light);">∞ Unlimited Token</span>
                        @else
                            {{ number_format($owner?->getTokenBalanceAmount() ?? 0) }} <span style="font-size: 18px; font-weight: 600; color: var(--text-secondary);">Token</span>
                        @endif
                    </h2>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                        Token digunakan untuk meng-generate soal otomatis dengan AI dan menjalankan sesi kuis.
                    </p>
                </div>
            </div>
            <div>
                <a href="#packages-section" class="btn btn-primary" style="padding: 12px 24px; font-size: 15px;">
                    <i class="fas fa-plus-circle"></i> Beli Paket Token
                </a>
            </div>
        </div>
    </div>

    <!-- Section: Paket Token Tersedia -->
    <div id="packages-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white);">Pilih Paket Token</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Pilih paket token yang sesuai dengan kebutuhan kuis dan evaluasi Anda.</p>
            </div>
        </div>

        @if($packages->isEmpty())
            <div class="card" style="padding: 40px; text-align: center;">
                <i class="fas fa-box-open" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h4 style="font-size: 16px; font-weight: 600; color: var(--text-white);">Belum Ada Paket Token Tersedia</h4>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">SuperAdmin belum mengaktifkan paket token. Silakan hubungi admin.</p>
            </div>
        @else
            <div class="grid-3">
                @foreach($packages as $pkg)
                    @php
                        $pricePerToken = $pkg->token_amount > 0 ? $pkg->price_idr / $pkg->token_amount : 0;
                    @endphp
                    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; position: relative; {{ $loop->iteration === 2 ? 'border-color: var(--primary); box-shadow: 0 0 25px rgba(99,102,241,0.15);' : '' }}">
                        @if($loop->iteration === 2)
                        <div style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, var(--primary), var(--accent)); color: white; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Populer
                        </div>
                        @endif

                        <div class="card-body">
                            <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--accent-light);">Paket {{ $pkg->name }}</span>
                            <div style="margin-top: 12px; display: flex; align-items: baseline; gap: 6px;">
                                <span style="font-size: 32px; font-weight: 800; color: var(--text-white);">{{ number_format($pkg->token_amount) }}</span>
                                <span style="font-size: 14px; font-weight: 600; color: var(--text-secondary);">Token</span>
                            </div>
                            
                            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--border);">
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                                    <span style="color: var(--text-muted);">Harga Paket:</span>
                                    <span style="font-weight: 700; color: var(--text-white); font-size: 16px;">Rp {{ number_format($pkg->price_idr, 0, ',', '.') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; margin-top: 6px;">
                                    <span style="color: var(--text-muted);">Harga per Token:</span>
                                    <span style="color: var(--success); font-weight: 600;">~Rp {{ number_format($pricePerToken, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if($pkg->description)
                            <p style="font-size: 13px; color: var(--text-muted); margin-top: 16px; line-height: 1.5;">
                                {{ $pkg->description }}
                            </p>
                            @endif
                        </div>

                        <div class="card-footer" style="background: rgba(0,0,0,0.1); justify-content: stretch;">
                            <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center;" 
                                onclick="openPurchaseModal('{{ $pkg->id }}', '{{ $pkg->name }}', '{{ number_format($pkg->token_amount) }}', '{{ number_format($pkg->price_idr, 0, ',', '.') }}')">
                                <i class="fas fa-shopping-cart"></i> Beli Paket Ini
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Section: Riwayat Transaksi Token -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history" style="color: var(--accent); margin-right: 8px;"></i> Riwayat Transaksi Token</h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <th>Tipe</th>
                        <th>Jumlah Token</th>
                        <th>Sumber / Gateway</th>
                        <th>Keterangan</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td style="font-size: 13px; color: var(--text-secondary);">
                                {{ $trx->created_at->format('d M Y, H:i') }}
                            </td>
                            <td>
                                @if($trx->type === 'credit')
                                    <span class="badge badge-success"><i class="fas fa-arrow-down"></i> Penambahan</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-arrow-up"></i> Penggunaan</span>
                                @endif
                            </td>
                            <td style="font-weight: 700; font-size: 15px; color: {{ $trx->type === 'credit' ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $trx->type === 'credit' ? '+' : '-' }}{{ number_format($trx->amount) }}
                            </td>
                            <td>
                                <span class="badge badge-info" style="text-transform: capitalize;">{{ str_replace('_', ' ', $trx->source) }}</span>
                            </td>
                            <td style="font-size: 13px; max-width: 300px;">
                                {{ $trx->note ?: '-' }}
                            </td>
                            <td style="font-size: 12px; font-family: monospace; color: var(--text-muted);">
                                {{ $trx->reference_id ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                Belum ada riwayat transaksi token.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer" style="justify-content: space-between;">
            <span style="font-size: 13px; color: var(--text-muted);">
                Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
            </span>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- Purchase Modal -->
<div id="purchase-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="width: 100%; max-width: 480px; box-shadow: var(--shadow-lg);">
        <div class="card-header">
            <h3>Konfirmasi Pembelian Token</h3>
            <button type="button" class="btn btn-icon btn-ghost" onclick="closePurchaseModal()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('tenant.owner.tokens.purchase', ['tenant' => $tenant]) }}">
            @csrf
            <input type="hidden" name="package_id" id="modal-package-id">

            <div class="card-body">
                <div style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: var(--text-muted);">Paket Dipilih:</span>
                        <span style="font-weight: 700; color: var(--text-white);" id="modal-package-name">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                        <span style="font-size: 13px; color: var(--text-muted);">Jumlah Token:</span>
                        <span style="font-weight: 700; color: var(--accent-light);" id="modal-token-amount">-</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; padding-top: 8px; border-top: 1px dashed rgba(99,102,241,0.3);">
                        <span style="font-size: 14px; font-weight: 600; color: var(--text-secondary);">Total Bayar:</span>
                        <span style="font-size: 18px; font-weight: 800; color: var(--success);" id="modal-price-idr">-</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Metode Pembayaran (Gateway):</label>
                    @if($gateways->isEmpty())
                        <div class="alert alert-warning" style="margin-bottom: 12px; font-size: 12px;">
                            Belum ada payment gateway dikonfigurasi. Mode simulasi akan digunakan.
                        </div>
                        <select name="gateway" class="form-select" required>
                            <option value="midtrans">Midtrans Snap (Simulasi / Sandbox)</option>
                            <option value="xendit">Xendit Invoice (Simulasi / Sandbox)</option>
                            <option value="ipaymu">iPaymu (Simulasi)</option>
                            <option value="doku">DOKU (Simulasi)</option>
                            <option value="duitku">Duitku (Simulasi)</option>
                        </select>
                    @else
                        <select name="gateway" class="form-select" required>
                            @foreach($gateways as $gw)
                                <option value="{{ $gw->gateway }}">{{ $gw->display_name ?: ucfirst($gw->gateway) }} ({{ ucfirst($gw->mode) }})</option>
                            @endforeach
                        </select>
                    @endif
                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">
                        Anda akan diarahkan ke halaman pembayaran atau simulasi sesuai dengan mode gateway.
                    </p>
                </div>
            </div>

            <div class="card-footer">
                <button type="button" class="btn btn-ghost" onclick="closePurchaseModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openPurchaseModal(id, name, tokens, price) {
        document.getElementById('modal-package-id').value = id;
        document.getElementById('modal-package-name').innerText = name;
        document.getElementById('modal-token-amount').innerText = tokens + ' Token';
        document.getElementById('modal-price-idr').innerText = 'Rp ' + price;
        document.getElementById('purchase-modal').style.display = 'flex';
    }

    function closePurchaseModal() {
        document.getElementById('purchase-modal').style.display = 'none';
    }

    // Close modal on escape or outside click
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePurchaseModal();
    });
    document.getElementById('purchase-modal').addEventListener('click', function(e) {
        if (e.target === this) closePurchaseModal();
    });
</script>
@endsection
