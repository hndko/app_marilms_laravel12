@extends('layouts.app-backend')

@section('title', 'Dashboard Owner')

@section('content')
<!-- TailAdmin Top Header Card Wrapper (Fix Image 3: Clear Card border & contrast) -->
<div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Dashboard Owner & Analytical Center</h2>
        <p class="text-xs text-gray-500">Monitoring real-time aktivitas kuis, peserta, dan saldo token AI.</p>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('tenant.owner.dashboard', ['tenant' => $tenant]) }}" 
        x-data="{ 
            period: '{{ $period }}',
            submitForm() {
                this.$el.submit();
            }
        }" class="flex items-center gap-2">
        <label for="period" class="text-xs font-bold text-gray-600 whitespace-nowrap">Filter Periode:</label>
        <select name="period" id="period" x-model="period" @change="submitForm()" 
            class="px-4 py-2.5 rounded-xl bg-gray-50/50 border border-gray-200 text-xs font-bold text-gray-800 shadow-2xs focus:outline-none focus:border-brand-500 focus:bg-white transition">
            <option value="hari_ini">Hari Ini</option>
            <option value="7_hari">7 Hari Terakhir</option>
            <option value="bulan_ini">Bulan Ini</option>
            <option value="tahun_ini">Tahun Ini</option>
            <option value="semua">Semua Waktu</option>
        </select>
    </form>
</div>

<!-- TailAdmin Redesigned Information Card (Fix Image 2: Clean Card contrast & icon styling) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs relative">
        <button @click="showInfoCard = false" class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-500 border border-brand-200 flex items-center justify-center font-bold shrink-0">
                <i class="fas fa-chalkboard-user text-lg"></i>
            </div>
            <div class="space-y-2 text-xs text-gray-600 leading-relaxed pr-6 flex-1">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-brand-50 text-brand-600 border border-brand-200">
                        PANDUAN MODUL
                    </span>
                    <h4 class="font-bold text-gray-900 text-sm">
                        Fungsi & Panduan Modul Owner Lembaga
                    </h4>
                </div>
                <p class="text-gray-600">
                    Portal Pengajar/Owner digunakan untuk mengelola kuis AI otomatis, mendaftarkan peserta ujian, memantau pengerjaan ujian real-time dengan proteksi anti-cheat, serta melakukan isi ulang (*top up*) saldo token AI.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 text-gray-700 font-semibold text-xs">
                        <i class="fas fa-magic text-brand-500"></i>
                        <span>Generator Kuis AI Instant</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 text-gray-700 font-semibold text-xs">
                        <i class="fas fa-user-gear text-success-600"></i>
                        <span>Impor Peserta & Password</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 text-gray-700 font-semibold text-xs">
                        <i class="fab fa-whatsapp text-success-500"></i>
                        <span>Notifikasi WhatsApp</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TailAdmin Welcome Banner -->
<div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-indigo-600 text-white shadow-theme-xs relative overflow-hidden">
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-200">
                PORTAL LEMBAGA & EVALUASI UJIAN
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ $owner?->name ?? 'Owner' }} 
                <span class="text-lg font-medium text-brand-100 block sm:inline">({{ $owner?->organization_name ?? 'MariLMS' }})</span>
            </h2>
            <p class="text-xs sm:text-sm text-brand-100/90 leading-relaxed">
                Kelola soal kuis otomatis dengan AI, pantau aktivitas peserta, dan analisis hasil evaluasi dalam satu dashboard terintegrasi.
            </p>
        </div>
        <div class="flex flex-wrap gap-3 shrink-0">
            <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
                class="px-4 py-2.5 rounded-xl bg-white text-brand-600 font-bold text-xs shadow-theme-xs hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fas fa-magic"></i>
                <span>Buat Kuis AI</span>
            </a>
            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" 
                class="px-4 py-2.5 rounded-xl bg-amber-400 text-amber-950 font-bold text-xs shadow-theme-xs hover:bg-amber-300 transition flex items-center gap-2">
                <i class="fas fa-coins"></i>
                <span>Top Up Token</span>
            </a>
        </div>
    </div>
    <i class="fas fa-graduation-cap absolute -right-6 -bottom-8 text-9xl opacity-10 pointer-events-none"></i>
</div>

<!-- TailAdmin Metrics Grid (Exact TailAdmin Card Structure: Icon on top, flex items-end below) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Total Kuis -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
            <i class="fas fa-question text-xl"></i>
        </div>
        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 font-medium">Total Kuis</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_quizzes']) }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['quiz_growth'] >= 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                <i class="fas {{ $data['stats']['quiz_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ abs($data['stats']['quiz_growth']) }}%
            </span>
        </div>
    </div>

    <!-- Total Peserta -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
            <i class="fas fa-users text-xl"></i>
        </div>
        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 font-medium">Peserta Terdaftar</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_participants']) }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['participant_growth'] >= 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                <i class="fas {{ $data['stats']['participant_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ abs($data['stats']['participant_growth']) }}%
            </span>
        </div>
    </div>

    <!-- Sesi Ujian -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
            <i class="fas fa-stopwatch text-xl"></i>
        </div>
        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 font-medium">Sesi Ujian Dikerjakan</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_attempts']) }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $data['stats']['attempt_growth'] >= 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                <i class="fas {{ $data['stats']['attempt_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ abs($data['stats']['attempt_growth']) }}%
            </span>
        </div>
    </div>

    <!-- Rata-rata Skor -->
    <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-white to-amber-50/20 p-5 md:p-6 shadow-theme-xs">
        <div class="flex items-center justify-center w-12 h-12 bg-amber-100 rounded-xl text-amber-600">
            <i class="fas fa-shield-halved text-xl"></i>
        </div>
        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 font-medium">Rata-rata Skor</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ $data['stats']['avg_score'] }}% <span class="text-xs text-amber-600 font-normal">({{ $data['stats']['flagged_count'] }} flag)</span></h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700 border border-amber-200">
                Flag: {{ $data['stats']['flag_rate'] }}%
            </span>
        </div>
    </div>
</div>

<!-- Charts & Status Distribution Section (Fix Image 1: Fully Responsive Grid & Responsive Charts) -->
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
    <!-- Trend Chart (8 Cols on XL+) -->
    <div class="xl:col-span-8 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4 min-w-0 overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Grafik Tren Sesi Ujian & Performa</h3>
                <p class="text-xs text-gray-500">Volume ujian harian dan perkembangan rata-rata nilai peserta.</p>
            </div>
            <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">7 Hari Terakhir</span>
        </div>
        
        <div id="examTrendChart" class="w-full h-72"></div>
    </div>

    <!-- Status & Pass Rate Distribution (4 Cols on XL+) -->
    <div class="xl:col-span-4 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-6 min-w-0 overflow-hidden">
        <div>
            <h3 class="text-base font-bold text-gray-900">Distribusi Status Ujian</h3>
            <p class="text-xs text-gray-500">Persentase kelulusan dan status pengerjaan.</p>
        </div>

        <!-- Pass vs Fail Donut -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tingkat Kelulusan Ujian</h4>
            <div id="passFailDonutChart" class="w-full min-h-[180px] flex items-center justify-center"></div>
        </div>

        <!-- Status Breakdown Progress Bars -->
        <div class="space-y-3 pt-3 border-t border-gray-100">
            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Rincian Status Sesi</h4>
            
            <div class="space-y-2 text-xs">
                <div>
                    <div class="flex justify-between font-bold text-gray-700 mb-1">
                        <span>Selesai Tepat Waktu</span>
                        <span class="text-success-600">{{ $data['status_distribution']['submitted'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-success-500 h-2 rounded-full" style="width: {{ $data['stats']['total_attempts'] > 0 ? ($data['status_distribution']['submitted'] / $data['stats']['total_attempts']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-bold text-gray-700 mb-1">
                        <span>Kehabisan Waktu (Timeout)</span>
                        <span class="text-amber-600">{{ $data['status_distribution']['timeout'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $data['stats']['total_attempts'] > 0 ? ($data['status_distribution']['timeout'] / $data['stats']['total_attempts']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between font-bold text-gray-700 mb-1">
                        <span>Sedang Berjalan</span>
                        <span class="text-brand-600">{{ $data['status_distribution']['in_progress'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-brand-500 h-2 rounded-full" style="width: {{ $data['stats']['total_attempts'] > 0 ? ($data['status_distribution']['in_progress'] / $data['stats']['total_attempts']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Table: Recent Exam Attempts -->
<div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-gray-900">Hasil Sesi Ujian Terbaru Peserta</h3>
            <p class="text-xs text-gray-500">8 riwayat pengerjaan kuis terbaru beserta pengawasan kecurangan.</p>
        </div>
        <a href="{{ route('tenant.owner.reports', ['tenant' => $tenant]) }}" class="px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center gap-1.5 self-start sm:self-auto">
            <span>Lihat Semua Laporan</span>
            <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-[10px] font-bold border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3">Nama Peserta</th>
                    <th class="px-4 py-3">Judul Kuis</th>
                    <th class="px-4 py-3 text-center">Skor</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Anti-Cheat Flag</th>
                    <th class="px-4 py-3">Waktu Selesai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($data['recent_attempts'] as $attempt)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-4 py-3 font-bold text-gray-900">
                            {{ $attempt->user?->name ?? 'Peserta' }}
                            <span class="block text-[10px] text-gray-400 font-normal">{{ $attempt->user?->email ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $attempt->quiz?->title ?? 'Kuis' }}
                        </td>
                        <td class="px-4 py-3 text-center font-extrabold text-sm">
                            @if($attempt->score !== null)
                                <span class="{{ $attempt->score >= ($attempt->quiz?->passing_score ?? 70) ? 'text-success-600' : 'text-error-600' }}">
                                    {{ $attempt->score }}%
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($attempt->status === 'submitted')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-success-50 text-success-700 border border-success-200">Selesai</span>
                            @elseif($attempt->status === 'in_progress')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">Pengerjaan</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">{{ strtoupper($attempt->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($attempt->is_flagged)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-error-50 text-error-700 border border-error-200">
                                    <i class="fas fa-triangle-exclamation"></i> Flagged ({{ $attempt->end_reason ?? 'Pindah Tab' }})
                                </span>
                            @else
                                <span class="text-gray-400 text-[10px]"><i class="fas fa-check-circle text-success-500 mr-1"></i> Bersih</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 font-medium">
                            {{ $attempt->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 font-semibold">
                            Belum ada riwayat sesi pengerjaan ujian.
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
    // 1. ApexTrendChart Responsive Configuration
    const trendOptions = {
        series: [{
            name: 'Jumlah Sesi Ujian',
            type: 'column',
            data: @json($data['chart']['attempts'])
        }, {
            name: 'Rata-rata Skor (%)',
            type: 'line',
            data: @json($data['chart']['scores'])
        }],
        chart: {
            height: 280,
            type: 'line',
            toolbar: { show: false }
        },
        colors: ['#465fff', '#10b981'],
        stroke: { width: [0, 3], curve: 'smooth' },
        plotOptions: { bar: { columnWidth: '40%', borderRadius: 6 } },
        labels: @json($data['chart']['labels']),
        xaxis: {
            labels: {
                rotate: 0,
                style: { fontSize: '11px' }
            }
        },
        yaxis: [
            { 
                labels: { formatter: (val) => Math.round(val) },
                title: { text: 'Sesi Ujian', style: { fontSize: '11px' } }
            }, 
            { 
                opposite: true, 
                title: { text: 'Skor (%)', style: { fontSize: '11px' } }, 
                max: 100,
                min: 0,
                labels: { formatter: (val) => Math.round(val) + '%' }
            }
        ],
        legend: { position: 'top', horizontalAlign: 'center' },
        grid: { padding: { left: 10, right: 10 } }
    };
    new ApexCharts(document.querySelector("#examTrendChart"), trendOptions).render();

    // 2. Pass/Fail Donut Chart Responsive Configuration
    const donutOptions = {
        series: [{{ $data['pass_fail_distribution']['passed'] }}, {{ $data['pass_fail_distribution']['failed'] }}],
        labels: ['Lulus', 'Tidak Lulus'],
        chart: { type: 'donut', height: 220, width: '100%' },
        colors: ['#10b981', '#f43f5e'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: true }
    };
    new ApexCharts(document.querySelector("#passFailDonutChart"), donutOptions).render();
});
</script>
@endpush
