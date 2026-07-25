@extends('layouts.app-backend')

@section('title', 'Simulasi Pembayaran Sandbox')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Card Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-theme-xs space-y-6">
        
        <!-- Header Section -->
        <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center font-bold text-xl shrink-0">
                <i class="fas fa-laptop-code"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900">Sandbox Checkout Simulation</h3>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Gateway: {{ strtoupper($order->gateway) }}</span>
            </div>
        </div>

        <!-- Alert Info -->
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 space-y-1 text-xs">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <i class="fas fa-circle-info text-amber-600"></i>
                <span>Pengujian Mode Simulasi Sandbox</span>
            </div>
            <p class="text-amber-800 leading-relaxed">
                Anda melihat halaman ini karena kredensial API payment gateway berada dalam mode pengujian sandbox atau belum dikonfigurasi di environment produksi. Pilih tombol simulasi di bawah untuk menguji alur webhook dan kredit token otomatis.
            </p>
        </div>

        <!-- Order Summary Box -->
        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-3 text-xs">
            <h4 class="font-extrabold text-gray-900 uppercase tracking-wider text-[11px] pb-2 border-b border-gray-200">
                Detail Pesanan Token (#{{ substr($order->id, 0, 8) }}...)
            </h4>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center text-gray-600">
                    <span>Paket Token:</span>
                    <span class="font-bold text-gray-900">{{ $order->package?->name ?? 'Paket Token' }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span>Jumlah Token:</span>
                    <span class="font-bold text-amber-600">{{ number_format($order->token_amount) }} Token</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span>ID Referensi Gateway:</span>
                    <span class="font-mono text-gray-500">{{ $order->gateway_order_id ?: 'SIM_' . time() }}</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between items-center font-bold text-sm">
                    <span class="text-gray-900">Total Tagihan:</span>
                    <span class="text-success-600 text-base">Rp {{ number_format($order->amount_idr, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Simulation Action Choice -->
        <div class="space-y-3">
            <p class="text-xs font-bold text-gray-700 text-center">
                Pilih hasil simulasi pembayaran yang ingin diuji:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Simulate Success -->
                <form method="POST" action="{{ route('tenant.owner.tokens.simulate.process', ['tenant' => $tenant, 'order' => $order->id]) }}">
                    @csrf
                    <input type="hidden" name="action" value="success" />
                    <button type="submit" 
                        class="w-full py-3 rounded-xl bg-success-600 hover:bg-success-700 text-white font-bold text-xs shadow-theme-xs transition flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-sm"></i>
                        <span>Bayar & Berhasil (Success)</span>
                    </button>
                </form>

                <!-- Simulate Failed -->
                <form method="POST" action="{{ route('tenant.owner.tokens.simulate.process', ['tenant' => $tenant, 'order' => $order->id]) }}">
                    @csrf
                    <input type="hidden" name="action" value="failed" />
                    <button type="submit" 
                        class="w-full py-3 rounded-xl bg-error-50 hover:bg-error-100 text-error-700 border border-error-200 font-bold text-xs transition flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle text-sm"></i>
                        <span>Batalkan / Gagal (Failed)</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer Link -->
        <div class="pt-4 border-t border-gray-100 text-center">
            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" 
                class="text-xs font-bold text-gray-500 hover:text-gray-800 transition inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Halaman Token</span>
            </a>
        </div>
    </div>
</div>
@endsection
