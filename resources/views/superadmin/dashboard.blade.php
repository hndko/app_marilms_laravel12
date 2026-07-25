@extends('layouts.app-backend')

@section('title', 'Dashboard SuperAdmin')
@section('page-title', 'Dashboard SuperAdmin')

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
                <i class="fas fa-info-circle text-lg"></i>
            </div>
            <div class="space-y-2 text-xs text-slate-600 leading-relaxed pr-6">
                <h4 class="font-bold text-slate-900 text-sm">
                    Fungsi & Informasi Modul SuperAdmin
                </h4>
                <p>
                    Modul ini merupakan konsol pusat pengawasan seluruh platform MariLMS AI. SuperAdmin dapat memantau kesehatan sistem, statistik transaksi token AI, aktivitas owner, serta mengelola provider AI & payment gateway.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-1 font-medium text-slate-700">
                    <div class="flex items-center gap-2"><i class="fas fa-building text-blue-600"></i> Kelola Owner Lembaga</div>
                    <div class="flex items-center gap-2"><i class="fas fa-box text-emerald-600"></i> Katalog Paket Token</div>
                    <div class="flex items-center gap-2"><i class="fas fa-robot text-purple-600"></i> Provider AI OpenRouter</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TailAdmin Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 sm:gap-6">
    <!-- Total Owner -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Owner</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['total_owners']) }}</h3>
        <p class="text-[11px] font-medium text-slate-500">Lembaga terdaftar</p>
    </div>

    <!-- Owner Aktif -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Owner Aktif</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['active_owners']) }}</h3>
        <p class="text-[11px] font-medium text-emerald-600"><i class="fas fa-check-circle"></i> Berstatus aktif</p>
    </div>

    <!-- Token Terjual -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Token Terjual</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-coins"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['total_tokens_sold']) }}</h3>
        <p class="text-[11px] font-medium text-slate-500">Total kredit token</p>
    </div>

    <!-- Token Dikonsumsi -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Token Digunakan</span>
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-bolt"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['total_tokens_consumed']) }}</h3>
        <p class="text-[11px] font-medium text-slate-500">Generate kuis AI</p>
    </div>

    <!-- Total Pendapatan -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pendapatan</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
        <p class="text-[11px] font-medium text-blue-600">Total pembayaran</p>
    </div>

    <!-- Owner Nonaktif -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Nonaktif</span>
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-user-slash"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($stats['inactive_owners']) }}</h3>
        <p class="text-[11px] font-medium text-red-500">Owner disuspen</p>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-clock text-slate-400"></i>
            <h3 class="font-bold text-slate-900 text-sm">Aktivitas Terbaru Sistem</h3>
        </div>
        <a href="{{ route('superadmin.logs.index') }}" class="text-xs font-semibold text-brand-500 hover:text-brand-600 flex items-center gap-1">
            <span>Lihat Semua Log</span>
            <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-3 px-5">Waktu</th>
                    <th class="py-3 px-5">Aksi</th>
                    <th class="py-3 px-5">Deskripsi</th>
                    <th class="py-3 px-5">Peran User</th>
                    <th class="py-3 px-5">Alamat IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-xs text-slate-700">
                @forelse($recentActivities as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-medium text-slate-500 whitespace-nowrap">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 font-medium text-slate-900">
                            {{ $log->description }}
                        </td>
                        <td class="py-3.5 px-5 font-medium text-slate-600">
                            {{ $log->user_type ?? '-' }}
                        </td>
                        <td class="py-3.5 px-5 font-mono text-slate-500">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            <span>Belum ada aktivitas tercatat</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
