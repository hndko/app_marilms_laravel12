@extends('layouts.app-backend')

@section('title', 'Dashboard Owner')

@section('content')
<div x-data="{ showInfoModal: false }" class="space-y-6">

    <!-- TailAdmin Top Header Card Wrapper with Panduan Modul Button -->
    <div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Dashboard Owner & Analytical Center</h2>
            <p class="text-xs text-gray-500">Monitoring real-time aktivitas kuis, peserta, dan saldo token AI.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Information Modal Trigger Button (Fix: Compact & Space Saving) -->
            <button @click="showInfoModal = true" 
                class="px-3.5 py-2.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-circle-info text-brand-500 text-sm"></i>
                <span>Panduan Modul</span>
            </button>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('tenant.owner.dashboard', ['tenant' => $tenant]) }}" 
                x-data="{ 
                    period: '{{ $period }}',
                    submitForm() {
                        this.$el.submit();
                    }
                }" class="flex items-center gap-2">
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
    </div>

    <!-- TailAdmin Modal Information Component (Rule 5.E GEMINI.md) -->
    <div x-show="showInfoModal" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-xs"
        style="display: none;">
        
        <div @click.outside="showInfoModal = false"
            class="bg-white rounded-2xl border border-gray-200 shadow-2xl max-w-xl w-full p-6 space-y-5 relative animate-in fade-in zoom-in duration-150">
            
            <!-- Header Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-500 border border-brand-200 flex items-center justify-center font-bold shrink-0">
                        <i class="fas fa-chalkboard-user text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Panduan Modul Owner Lembaga</h3>
                        <p class="text-xs text-gray-500">Transparansi fitur, alur kerja, dan logika bisnis sistem.</p>
                    </div>
                </div>
                <button @click="showInfoModal = false" class="text-gray-400 hover:text-gray-600 text-sm p-1.5 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body Modal Content -->
            <div class="space-y-4 text-xs text-gray-600 leading-relaxed max-h-[60vh] overflow-y-auto pr-1">
                <!-- 1. Tujuan Modul -->
                <div class="space-y-1.5 bg-gray-50 p-3.5 rounded-xl border border-gray-200/80">
                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-bullseye text-brand-500"></i>
                        Fungsi & Tujuan Modul
                    </h4>
                    <p>
                        Modul Owner digunakan untuk mengelola seluruh aspek akademis lembaga Anda: membuat kuis otomatis dengan AI OpenRouter, mengimpor akun peserta, memantau pengerjaan ujian real-time dengan pengawasan tab-switch, serta mengelola saldo token AI.
                    </p>
                </div>

                <!-- 2. Panduan Tombol Utama -->
                <div class="space-y-2">
                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-border-all text-success-600"></i>
                        Panduan Tombol Utama
                    </h4>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white border border-gray-200">
                            <span class="px-2 py-1 rounded-md bg-brand-500 text-white text-[10px] font-bold shrink-0">Buat Kuis AI</span>
                            <span>Generate soal kuis pilihan ganda otomatis menggunakan AI berdasarkan materi/topik yang diinputkan.</span>
                        </li>
                        <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white border border-gray-200">
                            <span class="px-2 py-1 rounded-md bg-amber-400 text-amber-950 text-[10px] font-bold shrink-0">Top Up Token</span>
                            <span>Membeli paket saldo token AI via Payment Gateway (Midtrans, Xendit, Ipaymu, Doku, Duitku) untuk kuota generate kuis.</span>
                        </li>
                        <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white border border-gray-200">
                            <span class="px-2 py-1 rounded-md bg-emerald-600 text-white text-[10px] font-bold shrink-0">Kelola Peserta</span>
                            <span>Mengimpor daftar peserta kuis dan password login secara kolektif via berkas Excel/CSV.</span>
                        </li>
                    </ul>
                </div>

                <!-- 3. Logika Bisnis & Keamanan -->
                <div class="space-y-1.5 bg-brand-50/50 p-3.5 rounded-xl border border-brand-200/60 text-brand-950">
                    <h4 class="font-bold text-brand-900 flex items-center gap-2">
                        <i class="fas fa-shield-halved text-brand-600"></i>
                        Logika Bisnis & Keamanan Multi-Tenant
                    </h4>
                    <p>
                        Seluruh data kuis, pertanyaan, dan riwayat peserta diisolasi secara otomatis berdasarkan <strong>Tenant ID</strong> lembaga Anda via global scope Eloquent. Timer pengerjaan dihitung otoritatis di server dan kecurangan (pindah tab) akan otomatis memicu status <em>Flagged</em>.
                    </p>
                </div>
            </div>

            <!-- Footer Modal Button -->
            <div class="pt-3 border-t border-gray-100 flex justify-end">
                <button @click="showInfoModal = false" 
                    class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition">
                    Saya Mengerti
                </button>
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

    <!-- TailAdmin Metrics Grid -->
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

    <!-- Charts & Status Distribution Section -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <!-- Trend Chart -->
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

        <!-- Status & Pass Rate Distribution -->
        <div class="xl:col-span-4 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-6 min-w-0 overflow-hidden">
            <div>
                <h3 class="text-base font-bold text-gray-900">Distribusi Status Ujian</h3>
                <p class="text-xs text-gray-500">Persentase kelulusan dan status pengerjaan.</p>
            </div>

            <div class="space-y-3">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tingkat Kelulusan Ujian</h4>
                <div id="passFailDonutChart" class="w-full min-h-[180px] flex items-center justify-center"></div>
            </div>

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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. ApexTrendChart
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
        xaxis: { labels: { rotate: 0, style: { fontSize: '11px' } } },
        yaxis: [
            { labels: { formatter: (val) => Math.round(val) }, title: { text: 'Sesi Ujian', style: { fontSize: '11px' } } }, 
            { opposite: true, title: { text: 'Skor (%)', style: { fontSize: '11px' } }, max: 100, min: 0, labels: { formatter: (val) => Math.round(val) + '%' } }
        ],
        legend: { position: 'top', horizontalAlign: 'center' },
        grid: { padding: { left: 10, right: 10 } }
    };
    new ApexCharts(document.querySelector("#examTrendChart"), trendOptions).render();

    // 2. Pass/Fail Donut
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
