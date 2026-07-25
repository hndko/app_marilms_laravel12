@extends('layouts.app-backend')

@section('title', 'Beranda Ujian Peserta')
@section('page-title', 'Beranda Ujian')

@section('content')
<!-- Mandatory Information Card (Rule 5.E GEMINI.md) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 rounded-2xl bg-gradient-to-r from-blue-50 via-indigo-50 to-slate-50 border border-blue-200/80 shadow-xs relative">
        <button @click="showInfoCard = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-500 text-white flex items-center justify-center font-bold shrink-0 shadow-md shadow-brand-500/20">
                <i class="fas fa-user-graduate text-lg"></i>
            </div>
            <div class="space-y-2 text-xs text-slate-600 leading-relaxed pr-6">
                <h4 class="font-bold text-slate-900 text-sm">
                    Fungsi & Tata Cara Pengerjaan Ujian
                </h4>
                <p>
                    Portal Peserta digunakan untuk mengerjakan kuis/ujian online dari lembaga Anda. Pastikan koneksi internet Anda stabil. Ujian dilengkapi dengan sistem pengawasan anti-cheat server (*authoritative timer & tab switch detection*).
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 font-medium text-slate-700">
                    <div class="flex items-center gap-2"><i class="fas fa-shield-halved text-amber-600"></i> Dilarang Pindah Tab Browser</div>
                    <div class="flex items-center gap-2"><i class="fas fa-clock text-blue-600"></i> Waktu Berjalan Otomatis</div>
                    <div class="flex items-center gap-2"><i class="fas fa-square-poll-vertical text-emerald-600"></i> Nilai Kuis Instant</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Hero Card -->
<div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-brand-600 via-blue-600 to-indigo-700 text-white shadow-md relative overflow-hidden">
    <div class="relative z-10 space-y-4 max-w-2xl">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-blue-100 text-xs font-bold border border-white/10">
            <i class="fas fa-sparkles text-amber-300"></i> Portal Evaluasi Participant
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Selamat Datang, {{ auth('participant')->user()->name }}!
        </h1>
        <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
            Pilih kuis atau ujian yang tersedia di bawah ini. Kerjakan dengan jujur tanpa berpindah tab browser selama ujian berlangsung.
        </p>
        <div class="flex flex-wrap gap-3 pt-2">
            <a href="#available-quizzes" 
                class="px-4 py-2.5 rounded-xl bg-white text-brand-600 font-bold text-xs shadow-sm hover:bg-blue-50 transition-all flex items-center gap-2">
                <i class="fas fa-play"></i>
                <span>Lihat Kuis Tersedia</span>
            </a>
            <a href="{{ route('tenant.participant.history', ['tenant' => $tenant ?? request()->segment(1)]) }}" 
                class="px-4 py-2.5 rounded-xl bg-white/10 text-white font-bold text-xs hover:bg-white/20 transition-all flex items-center gap-2 border border-white/20">
                <i class="fas fa-history"></i>
                <span>Riwayat & Nilai</span>
            </a>
        </div>
    </div>
    <i class="fas fa-user-graduate absolute -right-6 -bottom-8 text-9xl opacity-10 pointer-events-none"></i>
</div>

<!-- Available Quizzes Section -->
<div id="available-quizzes" class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-brand-500"></i>
                <span>Kuis & Ujian Tersedia</span>
            </h3>
            <p class="text-xs text-slate-500">Daftar paket evaluasi aktif yang dapat Anda kerjakan</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
            {{ $quizzes->count() }} Kuis Aktif
        </span>
    </div>

    @if($quizzes->isEmpty())
        <div class="p-12 text-center rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-brand-500 flex items-center justify-center text-2xl font-bold mx-auto">
                <i class="fas fa-inbox"></i>
            </div>
            <h4 class="font-bold text-slate-900 text-sm">Belum Ada Kuis yang Aktif</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                Saat ini belum ada paket ujian yang dibuka oleh pengajar. Silakan cek kembali beberapa saat lagi.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($quizzes as $quiz)
                @php
                    $user = auth('participant')->user();
                    $remaining = $quiz->remainingAttempts($user);
                    $hasPassed = $quiz->hasUserPassed($user);
                    $inProgressAttempt = $quiz->attempts()->where('user_id', $user->id)->inProgress()->first();
                @endphp

                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between space-y-4 hover:border-blue-300 transition-all">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                                {{ $quiz->category ?: 'Umum' }}
                            </span>

                            @if($inProgressAttempt)
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                                    <i class="fas fa-spinner fa-spin"></i> Mengerjakan
                                </span>
                            @elseif($hasPassed)
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Lulus
                                </span>
                            @elseif($remaining !== null && $remaining <= 0)
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-700 border border-red-200 flex items-center gap-1">
                                    <i class="fas fa-lock"></i> Habis Kesempatan
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1">
                                    <i class="fas fa-play"></i> Tersedia
                                </span>
                            @endif
                        </div>

                        <h4 class="text-base font-bold text-slate-900 line-clamp-1">
                            {{ $quiz->title }}
                        </h4>

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed h-8">
                            {{ $quiz->description ?: 'Tidak ada deskripsi khusus untuk kuis ini.' }}
                        </p>

                        <div class="pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                            <div class="p-2 rounded-lg bg-slate-50">
                                <span class="text-[10px] text-slate-400 block font-semibold">SOAL</span>
                                <span class="font-bold text-slate-800">{{ $quiz->questions_count }}</span>
                            </div>
                            <div class="p-2 rounded-lg bg-slate-50">
                                <span class="text-[10px] text-slate-400 block font-semibold">DURASI</span>
                                <span class="font-bold text-slate-800">{{ $quiz->time_limit }} m</span>
                            </div>
                            <div class="p-2 rounded-lg bg-slate-50">
                                <span class="text-[10px] text-slate-400 block font-semibold">KKM</span>
                                <span class="font-bold text-emerald-600">{{ $quiz->passing_score }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        @if($inProgressAttempt)
                            <a href="{{ route('tenant.participant.quiz.show', ['tenant' => $tenant ?? request()->segment(1), 'quiz' => $quiz->id]) }}" 
                                class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-play-circle"></i>
                                <span>Lanjutkan Kuis</span>
                            </a>
                        @elseif($remaining !== null && $remaining <= 0)
                            <button disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed">
                                Kesempatan Mengerjakan Habis
                            </button>
                        @else
                            <a href="{{ route('tenant.participant.quiz.show', ['tenant' => $tenant ?? request()->segment(1), 'quiz' => $quiz->id]) }}" 
                                class="w-full py-2.5 px-4 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-play"></i>
                                <span>Mulai Kerjakan Ujian</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
