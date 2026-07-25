@extends('layouts.app-backend')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

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
                <i class="fas fa-chalkboard-user text-lg"></i>
            </div>
            <div class="space-y-2 text-xs text-slate-600 leading-relaxed pr-6">
                <h4 class="font-bold text-slate-900 text-sm">
                    Fungsi & Panduan Modul Owner Lembaga
                </h4>
                <p>
                    Portal Pengajar/Owner digunakan untuk mengelola kuis AI otomatis, mendaftarkan peserta ujian, memantau pengerjaan ujian real-time dengan proteksi anti-cheat, serta melakukan isi ulang (*top up*) saldo token AI.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 font-medium text-slate-700">
                    <div class="flex items-center gap-2"><i class="fas fa-magic text-blue-600"></i> Generator Kuis AI Instant</div>
                    <div class="flex items-center gap-2"><i class="fas fa-user-gear text-emerald-600"></i> Impor Peserta & Password</div>
                    <div class="flex items-center gap-2"><i class="fab fa-whatsapp text-green-600"></i> Notifikasi WhatsApp</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Banner -->
<div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-brand-600 via-blue-600 to-indigo-700 text-white shadow-md relative overflow-hidden">
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-200">
                Selamat Datang Kembali
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ $owner?->name ?? 'Owner' }} 
                <span class="text-lg font-medium text-blue-200 block sm:inline">({{ $owner?->organization_name ?? 'MariLMS' }})</span>
            </h2>
            <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                Kelola soal kuis otomatis dengan AI, pantau aktivitas peserta, dan analisis hasil evaluasi dalam satu dashboard terintegrasi.
            </p>
        </div>
        <div class="flex flex-wrap gap-3 shrink-0">
            <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
                class="px-4 py-2.5 rounded-xl bg-white text-brand-600 font-bold text-xs shadow-sm hover:bg-blue-50 transition-all flex items-center gap-2">
                <i class="fas fa-magic"></i>
                <span>Buat Kuis AI</span>
            </a>
            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" 
                class="px-4 py-2.5 rounded-xl bg-amber-400 text-amber-950 font-bold text-xs shadow-sm hover:bg-amber-300 transition-all flex items-center gap-2">
                <i class="fas fa-coins"></i>
                <span>Top Up Token</span>
            </a>
        </div>
    </div>
    <!-- Decorative Icon Background -->
    <i class="fas fa-graduation-cap absolute -right-6 -bottom-8 text-9xl opacity-10 pointer-events-none"></i>
</div>

<!-- TailAdmin Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <!-- Total Kuis -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Kuis</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-question-circle"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['total_quizzes'] ?? 0) }}</h3>
        <p class="text-xs font-semibold text-emerald-600 flex items-center gap-1.5">
            <i class="fas fa-check-circle"></i>
            <span>{{ number_format($stats['active_quizzes'] ?? 0) }} kuis aktif</span>
        </p>
    </div>

    <!-- Total Peserta -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Peserta</span>
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['total_participants'] ?? 0) }}</h3>
        <p class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
            <i class="fas fa-user-graduate"></i>
            <span>Peserta terdaftar</span>
        </p>
    </div>

    <!-- Sesi Hari Ini -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Sesi Ujian Hari Ini</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-stopwatch"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['total_attempts_today'] ?? 0) }}</h3>
        <p class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
            <i class="fas fa-calendar-alt"></i>
            <span>Bulan ini: {{ number_format($stats['total_attempts_month'] ?? 0) }} sesi</span>
        </p>
    </div>

    <!-- Saldo Token -->
    <div class="p-5 rounded-2xl bg-white border border-amber-300/80 shadow-xs space-y-3 bg-gradient-to-br from-white to-amber-50/50">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Saldo Token AI</span>
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-coins"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-slate-900 tracking-tight">
            @if($stats['is_unlimited'] ?? false)
                <span class="text-amber-600 text-2xl font-black">∞ Unlimited</span>
            @else
                {{ number_format($stats['token_balance'] ?? 0) }}
            @endif
        </h3>
        <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" 
            class="text-xs font-bold text-amber-700 hover:text-amber-800 flex items-center gap-1">
            <span>Beli / Top Up Token</span>
            <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
    </div>
</div>
@endsection
