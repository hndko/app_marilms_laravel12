@extends('layouts.owner')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard')

@section('content')
<div style="display: flex; flex-direction: column; gap: 32px;">

    <!-- Welcome Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(6,182,212,0.1)); border: 1px solid rgba(99,102,241,0.4); padding: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-light);">Selamat Datang Kembali</span>
                <h2 style="font-size: 28px; font-weight: 800; color: var(--text-white); margin-top: 4px;">
                    {{ $owner?->name ?? 'Owner' }} <span style="font-size: 18px; font-weight: 600; color: var(--text-secondary);">({{ $owner?->organization_name ?? 'MariLMS' }})</span>
                </h2>
                <p style="font-size: 14px; color: var(--text-muted); margin-top: 6px; max-width: 600px;">
                    Kelola soal kuis otomatis dengan AI, pantau aktivitas peserta, dan analisis hasil evaluasi dalam satu dashboard terintegrasi.
                </p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" class="btn btn-primary" style="padding: 12px 20px;">
                    <i class="fas fa-magic"></i> Buat Kuis AI
                </a>
                <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" class="btn btn-accent" style="padding: 12px 20px;">
                    <i class="fas fa-coins"></i> Top Up Token
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid-4">
        <!-- Total Kuis -->
        <div class="card" style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Total Kuis</span>
                    <h3 style="font-size: 32px; font-weight: 800; color: var(--text-white); margin-top: 8px;">{{ number_format($stats['total_quizzes'] ?? 0) }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99,102,241,0.15); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
            <div style="margin-top: 16px; font-size: 13px; color: var(--success); display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-check-circle"></i> {{ number_format($stats['active_quizzes'] ?? 0) }} kuis aktif & siap dikerjakan
            </div>
        </div>

        <!-- Total Peserta -->
        <div class="card" style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Total Peserta</span>
                    <h3 style="font-size: 32px; font-weight: 800; color: var(--text-white); margin-top: 8px;">{{ number_format($stats['total_participants'] ?? 0) }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(6,182,212,0.15); color: var(--accent-light); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div style="margin-top: 16px; font-size: 13px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-user-graduate"></i> Terdaftar di tenant Anda
            </div>
        </div>

        <!-- Sesi Hari Ini -->
        <div class="card" style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Sesi Hari Ini</span>
                    <h3 style="font-size: 32px; font-weight: 800; color: var(--text-white); margin-top: 8px;">{{ number_format($stats['total_attempts_today'] ?? 0) }}</h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.15); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fas fa-stopwatch"></i>
                </div>
            </div>
            <div style="margin-top: 16px; font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-calendar-alt"></i> Total bulan ini: {{ number_format($stats['total_attempts_month'] ?? 0) }} sesi
            </div>
        </div>

        <!-- Saldo Token -->
        <div class="card" style="padding: 24px; border-color: rgba(245,158,11,0.4); background: linear-gradient(135deg, var(--bg-card), rgba(245,158,11,0.05));">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Saldo Token</span>
                    <h3 style="font-size: 32px; font-weight: 800; color: var(--text-white); margin-top: 8px;">
                        @if($stats['is_unlimited'] ?? false)
                            <span style="color: var(--accent-light); font-size: 28px;">∞ Unlimited</span>
                        @else
                            {{ number_format($stats['token_balance'] ?? 0) }}
                        @endif
                    </h3>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245,158,11,0.15); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 22px;">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div style="margin-top: 16px;">
                <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" style="font-size: 13px; font-weight: 600; color: var(--warning); text-decoration: none; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-arrow-right"></i> Beli atau kelola token
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Access Section -->
    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bolt" style="color: var(--accent); margin-right: 8px;"></i> Aksi Cepat</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: var(--bg-input); border-radius: var(--radius-sm);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-wand-magic-sparkles" style="color: var(--primary-light); font-size: 20px;"></i>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 600; color: var(--text-white);">Generate Kuis AI</h4>
                            <span style="font-size: 12px; color: var(--text-muted);">Buat soal kuis dari topik/materi dalam hitungan detik</span>
                        </div>
                    </div>
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" class="btn btn-sm btn-primary">Mulai</a>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: var(--bg-input); border-radius: var(--radius-sm);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-user-plus" style="color: var(--accent-light); font-size: 20px;"></i>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 600; color: var(--text-white);">Undang Peserta Baru</h4>
                            <span style="font-size: 12px; color: var(--text-muted);">Bagikan link undangan atau impor dari file CSV</span>
                        </div>
                    </div>
                    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}" class="btn btn-sm btn-ghost">Kelola</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle" style="color: var(--info); margin-right: 8px;"></i> Informasi Tenant</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                        <span style="color: var(--text-muted);">Nama Organisasi:</span>
                        <span style="font-weight: 600; color: var(--text-white);">{{ $owner?->organization_name ?? 'MariLMS' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                        <span style="color: var(--text-muted);">URL Tenant:</span>
                        <span style="font-family: monospace; color: var(--accent-light);">{{ url('/' . $tenant) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                        <span style="color: var(--text-muted);">Tipe Akun:</span>
                        <span class="badge badge-primary">{{ $owner?->isUnlimited() ? 'Unlimited VIP' : 'Regular Token' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Status Akun:</span>
                        <span class="badge badge-success">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
