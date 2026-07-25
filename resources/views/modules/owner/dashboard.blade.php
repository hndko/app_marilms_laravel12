@extends('layouts.app-backend')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

@section('content')
<!-- Mandatory Information Card (Rule 5.E GEMINI.md) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 rounded-2xl bg-brand-50/60 border border-brand-200/80 shadow-theme-xs relative">
        <button @click="showInfoCard = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-500 text-white flex items-center justify-center font-bold shrink-0 shadow-theme-xs">
                <i class="fas fa-chalkboard-user text-xl"></i>
            </div>
            <div class="space-y-2 text-xs text-gray-600 leading-relaxed pr-6">
                <h4 class="font-bold text-gray-900 text-sm">
                    Fungsi & Panduan Modul Owner Lembaga
                </h4>
                <p>
                    Portal Pengajar/Owner digunakan untuk mengelola kuis AI otomatis, mendaftarkan peserta ujian, memantau pengerjaan ujian real-time dengan proteksi anti-cheat, serta melakukan isi ulang (*top up*) saldo token AI.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 font-medium text-gray-700">
                    <div class="flex items-center gap-2"><i class="fas fa-magic text-brand-500"></i> Generator Kuis AI Instant</div>
                    <div class="flex items-center gap-2"><i class="fas fa-user-gear text-success-600"></i> Impor Peserta & Password</div>
                    <div class="flex items-center gap-2"><i class="fab fa-whatsapp text-success-500"></i> Notifikasi WhatsApp</div>
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
                SELAMAT DATANG KEMBALI
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
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-500 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-question-circle"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 py-0.5 px-2.5 text-xs font-medium text-success-600">
                <i class="fas fa-check-circle"></i> {{ number_format($stats['active_quizzes'] ?? 0) }} aktif
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOTAL KUIS</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($stats['total_quizzes'] ?? 0) }}</h4>
        </div>
    </div>

    <!-- Total Peserta -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-users"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 py-0.5 px-2.5 text-xs font-medium text-gray-600">
                <i class="fas fa-user-graduate"></i> Terdaftar
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOTAL PESERTA</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($stats['total_participants'] ?? 0) }}</h4>
        </div>
    </div>

    <!-- Sesi Ujian Hari Ini -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-success-50 text-success-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-stopwatch"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 py-0.5 px-2.5 text-xs font-medium text-gray-600">
                <i class="fas fa-calendar-alt"></i> Bulan ini: {{ number_format($stats['total_attempts_month'] ?? 0) }}
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">SESI UJIAN HARI INI</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($stats['total_attempts_today'] ?? 0) }}</h4>
        </div>
    </div>

    <!-- Saldo Token AI -->
    <div class="rounded-2xl border border-amber-300/80 bg-gradient-to-br from-white to-amber-50/40 p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-coins"></i>
            </div>
            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" 
                class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 hover:text-amber-800">
                <span>Top Up</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">SALDO TOKEN AI</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">
                @if($stats['is_unlimited'] ?? false)
                    <span class="text-amber-600 font-black">∞ Unlimited</span>
                @else
                    {{ number_format($stats['token_balance'] ?? 0) }}
                @endif
            </h4>
        </div>
    </div>
</div>
@endsection
