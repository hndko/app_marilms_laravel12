@extends('layouts.app-backend')

@section('title', 'Beranda Kuis & Ujian')
@section('page-title', 'Beranda Ujian Peserta')

@section('content')
<!-- Mandatory Information Card (Rule 5.E GEMINI.md) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 rounded-2xl bg-brand-50/60 border border-brand-200/80 shadow-theme-xs relative">
        <button @click="showInfoCard = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-success-600 text-white flex items-center justify-center font-bold shrink-0 shadow-theme-xs">
                <i class="fas fa-user-graduate text-xl"></i>
            </div>
            <div class="space-y-2 text-xs text-gray-600 leading-relaxed pr-6">
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

<!-- TailAdmin Welcome Card -->
<div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-success-600 via-teal-600 to-brand-600 text-white shadow-theme-xs relative overflow-hidden">
    <div class="relative z-10 space-y-2">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-200">
            PORTAL PESERTA UJIAN
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Selamat Datang, {{ auth('participant')->user()->name ?? 'Peserta' }}!
        </h2>
        <p class="text-xs sm:text-sm text-emerald-100 max-w-2xl leading-relaxed">
            Pilih daftar kuis di bawah ini untuk memulai sesi pengerjaan ujian. Pastikan koneksi internet Anda stabil.
        </p>
    </div>
    <i class="fas fa-pen-ruler absolute -right-6 -bottom-8 text-9xl opacity-10 pointer-events-none"></i>
</div>

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
