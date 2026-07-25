@extends('layouts.app-backend')

@section('title', 'Beranda Kuis & Ujian')

@section('content')
<div x-data="{ showInfoModal: false }" class="space-y-6">

    <!-- TailAdmin Top Header Card Wrapper with Panduan Modul Button -->
    <div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Beranda Ujian & Evaluasi Peserta</h2>
            <p class="text-xs text-gray-500">Pantau perkembangan nilai, kuis yang ditugaskan, dan riwayat pengerjaan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Information Modal Trigger Button (Fix: Compact & Space Saving) -->
            <button @click="showInfoModal = true" 
                class="px-3.5 py-2.5 rounded-xl bg-success-50 hover:bg-success-100 text-success-700 border border-success-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-circle-info text-success-600 text-sm"></i>
                <span>Panduan Ujian</span>
            </button>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('tenant.participant.dashboard', ['tenant' => $tenant]) }}" 
                x-data="{ 
                    period: '{{ $period }}',
                    submitForm() {
                        this.$el.submit();
                    }
                }" class="flex items-center gap-2">
                <select name="period" id="period" x-model="period" @change="submitForm()" 
                    class="px-4 py-2.5 rounded-xl bg-gray-50/50 border border-gray-200 text-xs font-bold text-gray-800 shadow-2xs focus:outline-none focus:border-success-600 focus:bg-white transition">
                    <option value="7_hari">7 Hari Terakhir</option>
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="semua">Semua Riwayat</option>
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
                    <div class="w-10 h-10 rounded-xl bg-success-50 text-success-600 border border-success-200 flex items-center justify-center font-bold shrink-0">
                        <i class="fas fa-user-graduate text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Panduan Peserta Ujian</h3>
                        <p class="text-xs text-gray-500">Petunjuk pengerjaan ujian dan sistem pengawasan.</p>
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
                        <i class="fas fa-bullseye text-success-600"></i>
                        Fungsi & Petunjuk Ujian
                    </h4>
                    <p>
                        Pilih kuis yang tersedia untuk memulai ujian. Selama pengerjaan ujian, sisa waktu dihitung secara otomatis oleh server. Pastikan jaringan internet Anda stabil.
                    </p>
                </div>

                <!-- 2. Proteksi Anti-Cheat -->
                <div class="space-y-1.5 bg-amber-50/60 p-3.5 rounded-xl border border-amber-200/60 text-amber-950">
                    <h4 class="font-bold text-amber-900 flex items-center gap-2">
                        <i class="fas fa-triangle-exclamation text-amber-600"></i>
                        Aturan Anti-Kecurangan (Anti-Cheat)
                    </h4>
                    <p>
                        Dilarang berpindah tab browser atau menutup jendela saat ujian berlangsung. Setiap aktivitas pindah tab akan dicatat sebagai <strong>Flagged Activity</strong> dan dapat membatalkan sesi ujian Anda.
                    </p>
                </div>
            </div>

            <!-- Footer Modal Button -->
            <div class="pt-3 border-t border-gray-100 flex justify-end">
                <button @click="showInfoModal = false" 
                    class="px-5 py-2.5 rounded-xl bg-success-600 hover:bg-success-700 text-white font-bold text-xs shadow-theme-xs transition">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- TailAdmin Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Ujian Selesai -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
                <i class="fas fa-circle-check text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Ujian Selesai</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['total_completed']) }}</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-brand-50 py-0.5 px-2.5 text-xs font-bold text-brand-600">
                    Terverifikasi
                </span>
            </div>
        </div>

        <!-- Rata-rata Skor -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
                <i class="fas fa-star text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Rata-rata Skor</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ $data['stats']['avg_score'] }}%</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 px-2.5 text-xs font-bold text-success-700">
                    Avg Score
                </span>
            </div>
        </div>

        <!-- Total Lulus -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl text-gray-800">
                <i class="fas fa-award text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Sesi Lulus Ujian</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['passed_count']) }}</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-emerald-50 py-0.5 px-2.5 text-xs font-bold text-emerald-700">
                    {{ number_format($data['stats']['failed_count']) }} belum lulus
                </span>
            </div>
        </div>

        <!-- Anti-Cheat Warnings -->
        <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-white to-amber-50/20 p-5 md:p-6 shadow-theme-xs">
            <div class="flex items-center justify-center w-12 h-12 bg-amber-100 rounded-xl text-amber-600">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 font-medium">Flag Peringatan</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm">{{ number_format($data['stats']['flagged_count']) }} kali</h4>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-amber-50 py-0.5 px-2.5 text-xs font-bold text-amber-700 border border-amber-200">
                    Proteksi Ujian
                </span>
            </div>
        </div>
    </div>

    <!-- Score Progression Chart -->
    @if(count($data['chart']['scores'] ?? []) > 0)
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4 min-w-0 overflow-hidden">
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
