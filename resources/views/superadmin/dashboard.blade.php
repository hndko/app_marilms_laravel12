@extends('layouts.superadmin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="stat-grid">
    <div class="stat-card" style="--stat-color: #6366f1;">
        <div class="stat-card-icon" style="background: rgba(99,102,241,0.15); color: #818cf8;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-card-value">{{ number_format($stats['total_owners']) }}</div>
        <div class="stat-card-label">Total Owner</div>
    </div>

    <div class="stat-card" style="--stat-color: #10b981;">
        <div class="stat-card-icon" style="background: rgba(16,185,129,0.15); color: #34d399;">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-card-value">{{ number_format($stats['active_owners']) }}</div>
        <div class="stat-card-label">Owner Aktif</div>
    </div>

    <div class="stat-card" style="--stat-color: #f59e0b;">
        <div class="stat-card-icon" style="background: rgba(245,158,11,0.15); color: #fbbf24;">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-card-value">{{ number_format($stats['total_tokens_sold']) }}</div>
        <div class="stat-card-label">Token Terjual</div>
    </div>

    <div class="stat-card" style="--stat-color: #06b6d4;">
        <div class="stat-card-icon" style="background: rgba(6,182,212,0.15); color: #22d3ee;">
            <i class="fas fa-bolt"></i>
        </div>
        <div class="stat-card-value">{{ number_format($stats['total_tokens_consumed']) }}</div>
        <div class="stat-card-label">Token Dikonsumsi</div>
    </div>

    <div class="stat-card" style="--stat-color: #10b981;">
        <div class="stat-card-icon" style="background: rgba(16,185,129,0.15); color: #34d399;">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        <div class="stat-card-label">Total Pendapatan</div>
    </div>

    <div class="stat-card" style="--stat-color: #ef4444;">
        <div class="stat-card-icon" style="background: rgba(239,68,68,0.15); color: #f87171;">
            <i class="fas fa-user-slash"></i>
        </div>
        <div class="stat-card-value">{{ number_format($stats['inactive_owners']) }}</div>
        <div class="stat-card-label">Owner Nonaktif</div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clock" style="color: var(--text-muted); margin-right: 8px;"></i>Aktivitas Terbaru</h3>
        <a href="{{ route('superadmin.logs.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>User</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $log)
                    <tr>
                        <td style="white-space: nowrap; color: var(--text-muted); font-size: 13px;">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $log->action }}</span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td style="color: var(--text-secondary); font-size: 13px;">
                            {{ $log->user_type ?? '-' }}
                        </td>
                        <td style="color: var(--text-muted); font-size: 13px; font-family: monospace;">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                            Belum ada aktivitas tercatat
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
