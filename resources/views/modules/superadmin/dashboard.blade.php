@extends('layouts.app-backend')

@section('title', 'Dashboard SuperAdmin')
@section('page-title', 'Dashboard SuperAdmin Central')

@section('content')
<!-- Mandatory Information Card (Rule 5.E GEMINI.md) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 rounded-2xl bg-brand-50/60 border border-brand-200/80 shadow-theme-xs relative">
        <button @click="showInfoCard = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold shrink-0 shadow-theme-xs">
                <i class="fas fa-user-shield text-xl"></i>
            </div>
            <div class="space-y-2 text-xs text-gray-600 leading-relaxed pr-6">
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
            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 py-0.5 px-2.5 text-xs font-medium text-success-600">
                <i class="fas fa-check-circle"></i> Terverifikasi
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOTAL OWNER LEMBAGA</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($stats['total_owners'] ?? 0) }}</h4>
        </div>
    </div>

    <!-- Total Tenants -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-500 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-network-wired"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 py-0.5 px-2.5 text-xs font-medium text-brand-600">
                <i class="fas fa-server"></i> Single-DB
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOTAL TENANT AKTIF</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($stats['total_tenants'] ?? 0) }}</h4>
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
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">TOTAL KUIS PLATFORM</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">{{ number_format($stats['total_quizzes'] ?? 0) }}</h4>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50/30 p-5 md:p-6 shadow-theme-xs space-y-4">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 py-0.5 px-2.5 text-xs font-bold text-emerald-600">
                Gateways Active
            </span>
        </div>
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-gray-500">ESTIMASI REVENUE TOKEN</span>
            <h4 class="mt-1 font-bold text-gray-900 text-title-sm">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>
@endsection
