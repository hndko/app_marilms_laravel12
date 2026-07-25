@extends('layouts.app-backend')

@section('title', 'Saldo Token & Pembelian Paket AI')

@section('content')
<div x-data="{ 
    showInfoModal: false,
    showPurchaseModal: false,
    selectedPkgId: null,
    selectedPkgName: '',
    selectedTokens: '',
    selectedPrice: '',
    openPurchase(id, name, tokens, price) {
        this.selectedPkgId = id;
        this.selectedPkgName = name;
        this.selectedTokens = tokens;
        this.selectedPrice = price;
        this.showPurchaseModal = true;
    },
    init() {
        this.$watch('showInfoModal', val => document.body.style.overflow = val ? 'hidden' : 'unset');
        this.$watch('showPurchaseModal', val => document.body.style.overflow = val ? 'hidden' : 'unset');
    }
}" class="space-y-6">

    <!-- TailAdmin Top Header Card Wrapper with Panduan Modul Button -->
    <div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Saldo Token & Pembelian Paket AI</h2>
            <p class="text-xs text-gray-500">Kelola saldo token AI Anda, beli paket token baru, dan pantau riwayat mutasi kredit/debit.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Information Modal Trigger Button -->
            <button @click="showInfoModal = true" 
                class="px-3.5 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-circle-info text-amber-600 text-sm"></i>
                <span>Panduan Modul</span>
            </button>
        </div>
    </div>

    <!-- Teleport Panduan Modul to Body -->
    <template x-teleport="body">
        <div x-show="showInfoModal" x-cloak
            @keydown.escape.window="showInfoModal = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 bg-gray-900/60 backdrop-blur-md overflow-y-auto"
            style="display: none;">
            
            <!-- Backdrop Click to Close -->
            <div @click="showInfoModal = false" class="fixed inset-0 h-full w-full"></div>

            <!-- Modal Dialog Box -->
            <div class="relative w-full max-w-[580px] rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-gray-200 z-10 flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-150">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-coins text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Panduan Modul Token & Top-Up</h3>
                            <p class="text-xs text-gray-500">Informasi pengisian saldo, mutasi, dan alur pembayaran.</p>
                        </div>
                    </div>
                    <button @click="showInfoModal = false" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto py-4 space-y-4 text-xs text-gray-600 leading-relaxed pr-2">
                    <div class="space-y-1.5 bg-gray-50 p-4 rounded-2xl border border-gray-200/80">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-bullseye text-amber-600"></i>
                            Fungsi & Kegunaan Token AI
                        </h4>
                        <p>
                            Token AI digunakan untuk meng-generate soal kuis pilihan ganda secara otomatis menggunakan model kecerdasan buatan (LLM OpenRouter/OpenAI). Setiap pembuatan kuis akan mengurangi kuota saldo token Anda secara atomik.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-credit-card text-brand-500"></i>
                            Metode Pembayaran (Payment Gateway)
                        </h4>
                        <div class="space-y-2">
                            <div class="p-3 rounded-2xl bg-white border border-gray-200 space-y-1">
                                <span class="font-bold text-gray-900">Gateway Resmi & Midtrans Snap / Xendit / iPaymu / DOKU / Duitku</span>
                                <p>Sistem mendukung verifikasi otomatis via webhook. Setelah pembayaran diselesaikan, saldo token akan langsung otomatis bertambah (*credited*) ke akun lembaga Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="pt-4 border-t border-gray-100 flex justify-end shrink-0">
                    <button @click="showInfoModal = false" 
                        class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-theme-xs transition">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- TailAdmin Saldo Token Banner Card -->
    <div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-600 to-indigo-600 text-white shadow-theme-xs relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-200">
                    SALDO TOKEN AI SAAT INI
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight flex items-center gap-3">
                    @if($owner?->isUnlimited())
                        <span class="text-amber-100"><i class="fas fa-infinity"></i> Unlimited Token</span>
                    @else
                        <span>{{ number_format($owner?->getTokenBalanceAmount() ?? 0) }}</span>
                        <span class="text-lg font-semibold text-amber-200">Token</span>
                    @endif
                </h2>
                <p class="text-xs sm:text-sm text-amber-100/90 leading-relaxed">
                    Saldo token ini digunakan untuk meng-generate soal kuis otomatis berbasis AI untuk instansi <strong>{{ $owner?->organization_name ?? tenant('name') ?? 'MariLMS' }}</strong>.
                </p>
            </div>
            <div class="shrink-0">
                <a href="#packages-section" 
                    class="px-5 py-3 rounded-xl bg-white text-amber-950 font-bold text-xs shadow-theme-xs hover:bg-amber-50 transition flex items-center gap-2">
                    <i class="fas fa-plus-circle text-amber-600 text-sm"></i>
                    <span>Top Up / Beli Paket Token</span>
                </a>
            </div>
        </div>
        <i class="fas fa-coins absolute -right-6 -bottom-8 text-9xl opacity-10 pointer-events-none"></i>
    </div>

    <!-- Section: Katalog Paket Token -->
    <div id="packages-section" class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">Katalog Paket Token AI</h3>
                <p class="text-xs text-gray-500">Pilih paket isi ulang token yang sesuai dengan kebutuhan kuis lembaga Anda.</p>
            </div>
        </div>

        @if($packages->isEmpty())
            <div class="p-8 rounded-2xl border border-dashed border-gray-300 bg-white text-center space-y-3">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center text-xl">
                    <i class="fas fa-box-open"></i>
                </div>
                <p class="text-xs font-semibold text-gray-500">Belum ada paket token yang diaktifkan oleh SuperAdmin central.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($packages as $pkg)
                    @php
                        $pricePerToken = $pkg->token_amount > 0 ? $pkg->price_idr / $pkg->token_amount : 0;
                        $isPopular = $loop->iteration === 2;
                    @endphp
                    <div class="rounded-2xl border bg-white p-6 shadow-theme-xs hover:border-amber-400 transition flex flex-col justify-between relative {{ $isPopular ? 'border-amber-400 ring-2 ring-amber-400/20' : 'border-gray-200' }}">
                        @if($isPopular)
                            <div class="absolute -top-3 right-6 bg-gradient-to-r from-amber-500 to-brand-500 text-white font-extrabold text-[10px] uppercase tracking-wider px-3 py-1 rounded-full shadow-2xs">
                                Paling Populer
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Paket {{ $pkg->name }}</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-extrabold text-gray-900">{{ number_format($pkg->token_amount) }}</span>
                                    <span class="text-xs font-semibold text-gray-500">Token</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Harga Paket:</span>
                                    <span class="font-extrabold text-gray-900 text-sm">Rp {{ number_format($pkg->price_idr, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Est. per Token:</span>
                                    <span class="font-bold text-success-600">~Rp {{ number_format($pricePerToken, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if($pkg->description)
                                <p class="text-xs text-gray-500 leading-relaxed line-clamp-3">
                                    {{ $pkg->description }}
                                </p>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-gray-100 mt-6">
                            <button type="button" 
                                @click="openPurchase('{{ $pkg->id }}', '{{ $pkg->name }}', '{{ number_format($pkg->token_amount) }}', '{{ number_format($pkg->price_idr, 0, ',', '.') }}')"
                                class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart text-xs"></i>
                                <span>Beli Paket Ini</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Teleport Pembelian Modal to Body -->
    <template x-teleport="body">
        <div x-show="showPurchaseModal" x-cloak
            @keydown.escape.window="showPurchaseModal = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 bg-gray-900/60 backdrop-blur-md overflow-y-auto"
            style="display: none;">
            
            <!-- Backdrop Click to Close -->
            <div @click="showPurchaseModal = false" class="fixed inset-0 h-full w-full"></div>

            <!-- Modal Dialog Box -->
            <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-gray-200 z-10 flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-150">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-cart-shopping text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Konfirmasi Pembelian Token</h3>
                            <p class="text-xs text-gray-500">Pilih saluran payment gateway untuk pembayaran.</p>
                        </div>
                    </div>
                    <button @click="showPurchaseModal = false" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form method="POST" action="{{ route('tenant.owner.tokens.purchase', ['tenant' => $tenant]) }}" class="space-y-4 pt-4">
                    @csrf
                    <input type="hidden" name="package_id" :value="selectedPkgId" />

                    <!-- Package Details Summary -->
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-2 text-xs">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Paket Dipilih:</span>
                            <span class="font-bold text-gray-900" x-text="selectedPkgName"></span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Jumlah Token:</span>
                            <span class="font-bold text-amber-600" x-text="selectedTokens + ' Token'"></span>
                        </div>
                        <div class="pt-2 border-t border-gray-200 flex justify-between items-center text-gray-900 font-bold">
                            <span>Total Pembayaran:</span>
                            <span class="text-sm text-success-600" x-text="'Rp ' + selectedPrice"></span>
                        </div>
                    </div>

                    <!-- Payment Gateway Selector -->
                    <div class="space-y-1.5">
                        <label for="gateway" class="block text-xs font-bold text-gray-700">
                            Pilih Metode Pembayaran (Gateway) <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-credit-card text-xs"></i>
                            </span>
                            @if($gateways->isEmpty())
                                <select name="gateway" id="gateway" required
                                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-amber-500 focus:bg-white focus:outline-none transition shadow-2xs">
                                    <option value="midtrans">Midtrans Snap (Simulasi / Sandbox)</option>
                                    <option value="xendit">Xendit Invoice (Simulasi / Sandbox)</option>
                                    <option value="ipaymu">iPaymu (Simulasi)</option>
                                    <option value="doku">DOKU (Simulasi)</option>
                                    <option value="duitku">Duitku (Simulasi)</option>
                                </select>
                            @else
                                <select name="gateway" id="gateway" required
                                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-amber-500 focus:bg-white focus:outline-none transition shadow-2xs">
                                    @foreach($gateways as $gw)
                                        <option value="{{ $gw->gateway }}">{{ $gw->display_name ?: ucfirst($gw->gateway) }} ({{ ucfirst($gw->mode) }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-400">Anda akan diarahkan ke instruksi pembayaran gateway yang dipilih.</p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="showPurchaseModal = false" 
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                            <i class="fas fa-credit-card text-xs"></i>
                            <span>Lanjutkan Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- TailAdmin Table: Riwayat Mutasi Token -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Riwayat Mutasi & Transaksi Token</h3>
                <p class="text-xs text-gray-500">Catatan penambahan saldo dan penggunaan token AI secara real-time.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Tanggal & Waktu</th>
                        <th class="px-4 py-3 text-center">Tipe Mutasi</th>
                        <th class="px-4 py-3 text-center">Jumlah Token</th>
                        <th class="px-4 py-3 text-center">Sumber / Gateway</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">ID Referensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-4 py-3 font-semibold text-gray-800">
                                {{ $trx->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($trx->type === 'credit')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-success-50 text-success-700 border border-success-200">
                                        <i class="fas fa-arrow-down-left mr-1"></i> Penambahan
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-error-50 text-error-700 border border-error-200">
                                        <i class="fas fa-arrow-up-right mr-1"></i> Penggunaan AI
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-extrabold text-sm {{ $trx->type === 'credit' ? 'text-success-600' : 'text-error-600' }}">
                                {{ $trx->type === 'credit' ? '+' : '-' }}{{ number_format($trx->amount) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 capitalize border border-gray-200">
                                    {{ str_replace('_', ' ', $trx->source) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 font-medium">
                                {{ $trx->note ?: '-' }}
                            </td>
                            <td class="px-4 py-3 font-mono text-[11px] text-gray-500">
                                {{ $trx->reference_id ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 font-semibold">
                                Belum ada riwayat transaksi mutasi token.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <span class="text-gray-500 font-medium">
                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </span>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
