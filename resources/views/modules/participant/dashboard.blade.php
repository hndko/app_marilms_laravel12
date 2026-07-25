@extends('layouts.app-backend')

@section('title', 'Beranda Kuis & Ujian')
@section('page-title', 'Beranda Ujian & Evaluasi Peserta')

@section('content')
<!-- Global Period Filter Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-gray-200 shadow-theme-xs">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-success-50 text-success-600 flex items-center justify-center font-bold">
            <i class="fas fa-graduation-cap text-lg"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold text-gray-900">Portal Evaluasi Ujian Peserta</h3>
            <p class="text-xs text-gray-500">Pantau perkembangan nilai, kuis yang ditugaskan, dan riwayat pengerjaan.</p>
        </div>
    </div>
    
    <!-- Filter Form -->
    <form method="GET" action="{{ route('tenant.participant.dashboard', ['tenant' => $tenant]) }}" 
        x-data="{ 
            period: '{{ $period }}',
            submitForm() {
                this.$el.submit();
            }
        }" class="flex items-center gap-2">
        <label for="period" class="text-xs font-bold text-gray-600 whitespace-nowrap">Filter Periode:</label>
        <select name="period" id="period" x-model="period" @change="submitForm()" 
            class="px-3.5 py-2 pr-8 rounded-xl bg-gray-50 border border-gray-200 text-xs font-bold text-gray-800 focus:outline-none focus:border-success-600 transition">
            <option value="7_hari">7 Hari Terakhir</option>
            <option value="bulan_ini">Bulan Ini</option>
            <option value="tahun_ini">Tahun Ini</option>
            <option value="semua">Semua Riwayat</option>
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
            <div class="w-10 h-10 rounded-xl bg-success-600 text-white flex items-center justify-center font-bold shrink-0 shadow-theme-xs">
                <i class="fas fa-user-graduate text-lg"></i>
            </div>
            <div class="space-y-1.5 text-xs text-gray-600 leading-relaxed pr-6">
                <h4 class="font-bold text-gray-900 text-sm">
                    Fungsi & Panduan Portal Peserta Ujian
                </h4>
                <p>
                    Portal Peserta Ujian digunakan untuk memilih kuis yang ditugaskan, memulai sesi pengerjaan ujian dengan proteksi anti-kecurangan (server timer & deteksi tab-switch), serta melihat hasil skor dan evaluasi pengerjaan.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 font-medium text-gray-700">
                    <div class="flex items-center gap-2"><i class="fas fa-play text-brand-500"></i> Kerjakan Ujian Real-time</div>
                    <div class="flex items-center gap-2"><i class="fas fa-shield-halved text-amber-500"></i> Proteksi Anti Cheat</div>
                    <div class="flex items-center gap-2"><i class="fas fa-square-poll-vertical text-success-600"></i> Hasil Nilai Otomatis</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TailAdmin Metrics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Ujian Selesai -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-500 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-circle-check"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 py-0.5 px-2.5 text-xs font-bold text-brand-600">
                Terverifikasi
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">UJIAN SELESAI</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($data['stats']['total_completed']) }}</h4>
        </div>
    </div>

    <!-- Rata-rata Skor -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-success-50 text-success-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-star"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 py-0.5 px-2.5 text-xs font-bold text-success-700">
                Rata-Rata
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">RATA-RATA NILAI SKOR</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ $data['stats']['avg_score'] }}%</h4>
        </div>
    </div>

    <!-- Total Lulus -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-award"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 py-0.5 px-2.5 text-xs font-bold text-emerald-700">
                Passing Score
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">SESI LULUS UJIAN</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($data['stats']['passed_count']) }} <span class="text-xs font-normal text-gray-500">({{ number_format($data['stats']['failed_count']) }} belum lulus)</span></h4>
        </div>
    </div>

    <!-- Anti-Cheat Warnings -->
    <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-white to-amber-50/30 p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 py-0.5 px-2.5 text-xs font-bold text-amber-700 border border-amber-200">
                Proteksi Ujian
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">PERINGATAN CHEAT FLAG</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($data['stats']['flagged_count']) }} kali</h4>
        </div>
    </div>
</div>

<!-- Score Progression Chart -->
@if(count($data['chart']['scores'] ?? []) > 0)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Grafik Perkembangan Nilai Ujian</h3>
                <p class="text-xs text-gray-500">Tren skor riwayat pengerjaan kuis terbaru Anda.</p>
            </div>
        </div>
        <div id="participantScoreChart" class="w-full h-64"></div>
    </div>
@endif

<!-- List Kuis Available Grid -->
<div class="space-y-4">
    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">
        Daftar Kuis Tersedia
    </h3>

    @if(count($quizzes ?? []) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($quizzes as $quiz)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs hover:border-brand-300 transition space-y-4 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-50 text-brand-600 border border-brand-200">
                                {{ $quiz->duration_minutes }} Menit
                            </span>
                            <span class="text-xs font-semibold text-gray-500">
                                Passing: {{ $quiz->passing_score }}%
                            </span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-base line-clamp-2">
                            {{ $quiz->title }}
                        </h4>
                        <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed">
                            {{ $quiz->description ?? 'Kuis evaluasi ujian pembelajaran.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-600">
                            <i class="fas fa-list-check text-brand-500 mr-1"></i> {{ $quiz->questions_count ?? count($quiz->questions ?? []) }} Soal
                        </span>
                        <a href="{{ route('tenant.participant.quiz.show', ['tenant' => tenant('slug'), 'quiz' => $quiz->id]) }}" 
                            class="px-3.5 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-1.5">
                            <span>Mulai Ujian</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-8 rounded-2xl border border-dashed border-gray-300 bg-white text-center space-y-3">
            <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl">
                <i class="fas fa-folder-open"></i>
            </div>
            <p class="text-xs font-semibold text-gray-500">Belum ada kuis yang tersedia untuk Anda saat ini.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if(count($data['chart']['scores'] ?? []) > 0)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const options = {
        series: [{
            name: 'Skor (%)',
            data: @json($data['chart']['scores'])
        }],
        chart: {
            height: 240,
            type: 'line',
            toolbar: { show: false }
        },
        colors: ['#10b981'],
        stroke: { width: 3, curve: 'smooth' },
        markers: { size: 5 },
        labels: @json($data['chart']['labels']),
        yaxis: { max: 100, min: 0 },
        dataLabels: { enabled: true }
    };
    new ApexCharts(document.querySelector("#participantScoreChart"), options).render();
});
</script>
@endif
@endpush
