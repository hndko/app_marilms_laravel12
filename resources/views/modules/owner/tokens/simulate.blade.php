@extends('layouts.owner')

@section('title', 'Simulasi Pembayaran')
@section('page-title', 'Simulasi Pembayaran Sandbox')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}">Token & Top-up</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Simulasi Pembayaran</span>
@endsection

@section('content')
<div style="max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <div class="card" style="border-color: var(--accent); box-shadow: 0 0 30px rgba(6,182,212,0.15);">
        <div class="card-header" style="background: linear-gradient(135deg, rgba(6,182,212,0.1), rgba(99,102,241,0.1)); border-bottom: 1px solid rgba(6,182,212,0.3);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-white);">Sandbox Checkout Simulation</h3>
                    <span style="font-size: 12px; color: var(--accent-light); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Gateway: {{ strtoupper($order->gateway) }}</span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-warning" style="margin-bottom: 24px;">
                <i class="fas fa-info-circle" style="font-size: 18px; flex-shrink: 0;"></i>
                <div>
                    <strong>Mode Simulasi / Sandbox</strong><br>
                    <span style="font-size: 12px; color: var(--text-secondary);">
                        Anda melihat halaman ini karena kredensial API payment gateway belum diatur untuk production atau Anda sedang dalam mode pengujian lokal. Gunakan tombol di bawah untuk menyimulasikan respons dari pihak gateway.
                    </span>
                </div>
            </div>

            <div style="background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 20px; margin-bottom: 24px;">
                <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
                    Detail Pesanan (#{{ substr($order->id, 0, 8) }}...)
                </h4>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
                        <span style="color: var(--text-muted);">Paket Token:</span>
                        <span style="font-weight: 700; color: var(--text-white);">{{ $order->package?->name ?? 'Paket Token' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
                        <span style="color: var(--text-muted);">Jumlah Token:</span>
                        <span style="font-weight: 700; color: var(--accent-light);">{{ number_format($order->token_amount) }} Token</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
                        <span style="color: var(--text-muted);">ID Referensi Gateway:</span>
                        <span style="font-family: monospace; color: var(--text-secondary); font-size: 12px;">{{ $order->gateway_order_id ?: 'SIM_' . time() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; margin-top: 4px; padding-top: 12px; border-top: 1px dashed var(--border);">
                        <span style="font-size: 15px; font-weight: 600; color: var(--text-white);">Total Tagihan:</span>
                        <span style="font-size: 20px; font-weight: 800; color: var(--success);">Rp {{ number_format($order->amount_idr, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <p style="font-size: 13px; font-weight: 600; color: var(--text-white); margin-bottom: 16px; text-align: center;">
                Pilih hasil simulasi pembayaran yang ingin diuji:
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <!-- Simulate Success -->
                <form method="POST" action="{{ route('tenant.owner.tokens.simulate.process', ['tenant' => $tenant, 'order' => $order->id]) }}">
                    @csrf
                    <input type="hidden" name="action" value="success">
                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 14px; justify-content: center; font-size: 14px; box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
                        <i class="fas fa-check-circle"></i> Bayar & Berhasil
                    </button>
                </form>

                <!-- Simulate Failed -->
                <form method="POST" action="{{ route('tenant.owner.tokens.simulate.process', ['tenant' => $tenant, 'order' => $order->id]) }}">
                    @csrf
                    <input type="hidden" name="action" value="failed">
                    <button type="submit" class="btn btn-danger" style="width: 100%; padding: 14px; justify-content: center; font-size: 14px; background: rgba(239,68,68,0.2); color: var(--danger); border: 1px solid var(--danger);">
                        <i class="fas fa-times-circle"></i> Batalkan / Gagal
                    </button>
                </form>
            </div>
        </div>

        <div class="card-footer" style="justify-content: center; background: rgba(0,0,0,0.1);">
            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" style="font-size: 13px; color: var(--text-muted); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali ke daftar paket token
            </a>
        </div>
    </div>

</div>
@endsection
