@extends('layouts.app-backend')

@section('title', 'Dashboard SuperAdmin')
@section('page-title', 'Dashboard SuperAdmin Central & Platform Analytics')

@section('content')
<!-- Global Period Filter Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-gray-200 shadow-theme-xs">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
            <i class="fas fa-chart-line text-lg"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold text-gray-900">Analisis Platform & Transaksi Token</h3>
            <p class="text-xs text-gray-500">Monitoring platform MariLMS AI central, pendapatan token sales, dan status gateway.</p>
        </div>
    </div>
    
    <!-- Filter Form -->
    <form method="GET" action="{{ route('superadmin.dashboard') }}" 
        x-data="{ 
            period: '{{ $period }}',
            submitForm() {
                this.$el.submit();
            }
        }" class="flex items-center gap-2">
        <label for="period" class="text-xs font-bold text-gray-600 whitespace-nowrap">Filter Periode:</label>
        <select name="period" id="period" x-model="period" @change="submitForm()" 
            class="px-3.5 py-2 pr-8 rounded-xl bg-gray-50 border border-gray-200 text-xs font-bold text-gray-800 focus:outline-none focus:border-purple-600 transition">
            <option value="hari_ini">Hari Ini</option>
            <option value="7_hari">7 Hari Terakhir</option>
            <option value="bulan_ini">Bulan Ini</option>
            <option value="tahun_ini">Tahun Ini</option>
            <option value="semua">Semua Waktu</option>
        </select>
    </form>
</div>

<!-- Mandatory Information Card (Rule 5.E GEMINI.md) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 rounded-2xl bg-brand-50/60 border border-brand-200/80 shadow-theme-xs relative">
        <button @click="showInfoCard = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold shrink-0 shadow-theme-xs">
                <i class="fas fa-user-shield text-lg"></i>
            </div>
            <div class="space-y-1.5 text-xs text-gray-600 leading-relaxed pr-6">
                <h4 class="font-bold text-gray-900 text-sm">
                    Fungsi & Panduan Modul SuperAdmin Central
                </h4>
                <p>
                    Portal Pengelola Utama MariLMS AI digunakan untuk memantau performa platform, mengelola pendaftaran Owner Lembaga, mengatur katalog Paket Token, mengkonfigurasi Provider AI LLM (OpenRouter), serta memantau log transaksi & aktivitas sistem.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 font-medium text-gray-700">
                    <div class="flex items-center gap-2"><i class="fas fa-building text-brand-500"></i> Kelola Owner & Tenant</div>
                    <div class="flex items-center gap-2"><i class="fas fa-box text-purple-600"></i> Katalog Paket Token</div>
                    <div class="flex items-center gap-2"><i class="fas fa-robot text-indigo-600"></i> Provider AI OpenRouter</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TailAdmin Metrics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Total Owner -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-building"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['owner_growth'] >= 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                <i class="fas {{ $data['stats']['owner_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ abs($data['stats']['owner_growth']) }}%
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOTAL OWNER LEMBAGA</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($data['stats']['total_owners']) }}</h4>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50/30 p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['revenue_growth'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-error-50 text-error-600' }}">
                <i class="fas {{ $data['stats']['revenue_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ abs($data['stats']['revenue_growth']) }}%
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">PENDAPATAN SALES TOKEN</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">Rp {{ number_format($data['stats']['total_revenue'], 0, ',', '.') }}</h4>
        </div>
    </div>

    <!-- Total Kuis Generated -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-magic"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 py-0.5 px-2.5 text-xs font-medium text-indigo-600">
                <i class="fas fa-robot"></i> AI Generated
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">KUIS AI DI-GENERATE</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($data['stats']['total_quizzes']) }}</h4>
        </div>
    </div>

    <!-- Total Token Sold/Consumed -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-coins"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 py-0.5 px-2.5 text-xs font-bold text-amber-700">
                Saldo Terjual
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOKEN TERJUAL / TERPAKAI</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($data['stats']['total_tokens_sold']) }} <span class="text-xs font-normal text-gray-500">({{ number_format($data['stats']['total_tokens_consumed']) }} terpakai)</span></h4>
        </div>
    </div>
</div>

<!-- Charts & Status Distribution Section -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Revenue Trend Chart (8 Cols) -->
    <div class="lg:col-span-8 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Grafik Tren Pendapatan Token Sales</h3>
                <p class="text-xs text-gray-500">Statistik harian transaksi order pembelian token paket oleh Owner.</p>
            </div>
            <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">7 Hari Terakhir</span>
        </div>
        
        <div id="revenueTrendChart" class="w-full h-72"></div>
    </div>

    <!-- Payment Order Status Distribution (4 Cols) -->
    <div class="lg:col-span-4 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-6">
        <div>
            <h3 class="text-base font-bold text-gray-900">Distribusi Status Order</h3>
            <p class="text-xs text-gray-500">Status transaksi payment gateway.</p>
        </div>

        <div id="orderStatusDonutChart" class="w-full h-44 flex items-center justify-center"></div>

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
        yaxis: { labels: { formatter: (val) => 'Rp ' + val.toLocaleString('id-ID') } },
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
        chart: { type: 'donut', height: 180 },
        colors: ['#10b981', '#f59e0b', '#f43f5e'],
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#orderStatusDonutChart"), donutOptions).render();
});
</script>
@endpush
