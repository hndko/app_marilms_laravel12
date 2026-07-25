@extends('layouts.app-backend')

@section('title', 'Dashboard SuperAdmin')

@section('content')
<div x-data="{ 
    showInfoModal: false,
    init() {
        this.$watch('showInfoModal', value => {
            document.body.style.overflow = value ? 'hidden' : 'unset';
        });
    }
}" class="space-y-6">

    <!-- TailAdmin Top Header Card Wrapper with Panduan Modul Button -->
    <div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Dashboard SuperAdmin Central</h2>
            <p class="text-xs text-gray-500">Monitoring platform MariLMS AI central, pendapatan token sales, dan status gateway.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Information Modal Trigger Button -->
            <button @click="showInfoModal = true" 
                class="px-3.5 py-2.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-circle-info text-purple-600 text-sm"></i>
                <span>Panduan Modul</span>
            </button>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('superadmin.dashboard') }}" 
                x-data="{ 
                    period: '{{ $period }}',
                    submitForm() {
                        this.$el.submit();
                    }
                }" class="flex items-center gap-2">
                <select name="period" id="period" x-model="period" @change="submitForm()" 
                    class="px-4 py-2.5 rounded-xl bg-gray-50/50 border border-gray-200 text-xs font-bold text-gray-800 shadow-2xs focus:outline-none focus:border-purple-600 focus:bg-white transition">
                    <option value="hari_ini">Hari Ini</option>
                    <option value="7_hari">7 Hari Terakhir</option>
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="semua">Semua Waktu</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Teleport Modal to Body to bypass stacking contexts -->
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
                
                <!-- Modal Header (Fixed Top) -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-user-shield text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Panduan SuperAdmin Central</h3>
                            <p class="text-xs text-gray-500">Transparansi fitur, alur kerja, dan kontrol platform central.</p>
                        </div>
                    </div>
                    <button @click="showInfoModal = false" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body (Dedicated Scrollable Container with Bottom Padding) -->
                <div class="flex-1 overflow-y-auto py-4 space-y-4 text-xs text-gray-600 leading-relaxed pr-2">
                    <!-- 1. Tujuan Modul -->
                    <div class="space-y-1.5 bg-gray-50 p-4 rounded-2xl border border-gray-200/80">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-bullseye text-purple-600"></i>
                            Fungsi & Tujuan Modul Central
                        </h4>
                        <p>
                            Portal SuperAdmin Central digunakan untuk mengelola seluruh ekosistem MariLMS AI: mendaftarkan Owner Lembaga baru, mengelola paket token AI yang dijual, mengatur API Key OpenRouter LLM, serta memantau log transaksi payment gateway.
                        </p>
                    </div>

                    <!-- 2. Panduan Tombol Utama -->
                    <div class="space-y-2">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-border-all text-brand-500"></i>
                            Fitur & Modul Utama
                        </h4>
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 p-3 rounded-2xl bg-white border border-gray-200">
                                <span class="px-2.5 py-1 rounded-lg bg-purple-600 text-white text-[10px] font-bold shrink-0 self-start sm:self-auto">Owner Lembaga</span>
                                <span>Menambah, menonaktifkan, atau memberikan status saldo token Unlimited pada Owner Lembaga.</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 p-3 rounded-2xl bg-white border border-gray-200">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-600 text-white text-[10px] font-bold shrink-0 self-start sm:self-auto">Provider LLM</span>
                                <span>Mengonfigurasi API Key OpenRouter, memilih model AI (OpenAI GPT-4o/Claude/Llama), dan menguji koneksi LLM.</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 p-3 rounded-2xl bg-white border border-gray-200">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[10px] font-bold shrink-0 self-start sm:self-auto">Payment Gateways</span>
                                <span>Mengatur kunci API Midtrans, Xendit, Ipaymu, Doku, Duitku untuk otomatisasi transaksi token.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer (Fixed Bottom outside Body Scroll Container) -->
                <div class="pt-4 border-t border-gray-100 flex justify-end shrink-0">
                    <button @click="showInfoModal = false" 
                        class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-theme-xs transition">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- TailAdmin Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Owner -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Owner Lembaga</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_owners']) }}</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['owner_growth'] >= 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                    <i class="fas {{ $data['stats']['owner_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    {{ abs($data['stats']['owner_growth']) }}%
                </span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50/20 p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-xl text-emerald-600">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Pendapatan Token Sales</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">Rp {{ number_format($data['stats']['total_revenue'], 0, ',', '.') }}</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['revenue_growth'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-error-50 text-error-600' }}">
                    <i class="fas {{ $data['stats']['revenue_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    {{ abs($data['stats']['revenue_growth']) }}%
                </span>
            </div>
        </div>

        <!-- Total Kuis Generated -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
                <i class="fas fa-magic text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Kuis AI Generated</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_quizzes']) }}</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-indigo-50 py-0.5 px-2.5 text-xs font-bold text-indigo-600">
                    AI Enabled
                </span>
            </div>
        </div>

        <!-- Total Token Sold/Consumed -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
                <i class="fas fa-coins text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Token Terjual</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_tokens_sold']) }}</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-amber-50 py-0.5 px-2.5 text-xs font-bold text-amber-700">
                    {{ number_format($data['stats']['total_tokens_consumed']) }} terpakai
                </span>
            </div>
        </div>
    </div>

    <!-- Charts & Status Distribution Section -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="xl:col-span-8 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4 min-w-0 overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Grafik Tren Pendapatan Token Sales</h3>
                    <p class="text-xs text-gray-500">Statistik harian transaksi order pembelian token paket oleh Owner.</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">7 Hari Terakhir</span>
            </div>
            
            <div id="revenueTrendChart" class="w-full h-72"></div>
        </div>

        <!-- Payment Order Status Distribution -->
        <div class="xl:col-span-4 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-6 min-w-0 overflow-hidden">
            <div>
                <h3 class="text-base font-bold text-gray-900">Distribusi Status Order</h3>
                <p class="text-xs text-gray-500">Status transaksi payment gateway.</p>
            </div>

            <div id="orderStatusDonutChart" class="w-full min-h-[180px] flex items-center justify-center"></div>

            <div class="space-y-2 pt-3 border-t border-gray-100 text-xs">
                <div class="flex justify-between font-bold text-gray-700">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Success</span>
                    <span class="text-emerald-600">{{ number_format($data['status_distribution']['success']) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-700">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span> Pending</span>
                    <span class="text-amber-600">{{ number_format($data['status_distribution']['pending']) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-700">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span> Failed/Expired</span>
                    <span class="text-rose-600">{{ number_format($data['status_distribution']['failed'] + $data['status_distribution']['expired']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table: Recent Token Orders -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Transaksi Order Token Terbaru</h3>
                <p class="text-xs text-gray-500">Daftar transaksi order pembelian token terbaru dari Owner Lembaga.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Owner Lembaga</th>
                        <th class="px-4 py-3">Paket Token</th>
                        <th class="px-4 py-3 text-center">Jumlah Token</th>
                        <th class="px-4 py-3 text-right">Nominal (IDR)</th>
                        <th class="px-4 py-3 text-center">Gateway</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Tanggal Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data['recent_orders'] as $order)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-4 py-3 font-bold text-gray-900">
                                {{ $order->owner?->name ?? 'Owner' }}
                                <span class="block text-[10px] text-gray-400 font-normal">{{ $order->owner?->organization_name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $order->package?->name ?? 'Paket Token' }}
                            </td>
                            <td class="px-4 py-3 text-center font-extrabold text-amber-600">
                                +{{ number_format($order->token_amount) }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                Rp {{ number_format($order->amount_idr, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center uppercase font-bold text-gray-700">
                                {{ $order->gateway }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($order->status === 'success')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-success-50 text-success-700 border border-success-200">Lunas</span>
                                @elseif($order->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-error-50 text-error-700 border border-error-200">{{ strtoupper($order->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 font-medium">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 font-semibold">
                                Belum ada riwayat transaksi order token.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Revenue Chart
    const revenueOptions = {
        series: [{
            name: 'Pendapatan (IDR)',
            data: @json($data['chart']['revenues'])
        }],
        chart: {
            height: 280,
            type: 'area',
            toolbar: { show: false }
        },
        colors: ['#10b981'],
        fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { width: 3, curve: 'smooth' },
        labels: @json($data['chart']['labels']),
        yaxis: { labels: { formatter: (val) => 'Rp ' + Math.round(val).toLocaleString('id-ID') } },
        dataLabels: { enabled: false }
    };
    new ApexCharts(document.querySelector("#revenueTrendChart"), revenueOptions).render();

    // 2. Order Donut
    const donutOptions = {
        series: [
            {{ $data['status_distribution']['success'] }}, 
            {{ $data['status_distribution']['pending'] }}, 
            {{ $data['status_distribution']['failed'] + $data['status_distribution']['expired'] }}
        ],
        labels: ['Success', 'Pending', 'Failed/Expired'],
        chart: { type: 'donut', height: 220, width: '100%' },
        colors: ['#10b981', '#f59e0b', '#f43f5e'],
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#orderStatusDonutChart"), donutOptions).render();
});
</script>
@endpush
