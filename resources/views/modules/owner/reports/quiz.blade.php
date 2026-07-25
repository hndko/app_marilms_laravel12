@extends('layouts.app-backend')

@section('title', 'Laporan Kuis: ' . $quiz->title)
@section('page-title', 'Analitik Kuis Detail')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.reports', ['tenant' => $tenant]) }}">Laporan</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>{{ Str::limit($quiz->title, 25) }}</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Header Card -->
    <div class="card" style="background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(99,102,241,0.15)); border: 1px solid rgba(6,182,212,0.3); padding: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span class="badge badge-info">{{ $quiz->category ?: 'Umum' }}</span>
                    <span class="badge badge-secondary">Passing Score: {{ $quiz->passing_score }}%</span>
                </div>
                <h1 style="font-size: 28px; font-weight: 800; color: white;">{{ $quiz->title }}</h1>
                <p style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">
                    Rincian statistik performa peserta, tingkat kelulusan, dan distribusi nilai untuk kuis ini.
                </p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('tenant.owner.reports.export', ['tenant' => $tenant, 'type' => 'csv', 'quiz_id' => $quiz->id]) }}" class="btn btn-primary" style="padding: 12px 24px; background: linear-gradient(135deg, var(--success), #059669);">
                    <i class="fas fa-file-csv"></i> Ekspor Laporan Kuis Ini (CSV)
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
        <div class="card" style="padding: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Percobaan</span>
            <span style="font-size: 24px; font-weight: 800; color: white; margin-top: 4px; display: block;">
                <i class="fas fa-clipboard-list" style="color: var(--primary-light); margin-right: 6px;"></i> {{ $stats['total_attempts'] }} Kali
            </span>
        </div>

        <div class="card" style="padding: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Selesai Dikerjakan</span>
            <span style="font-size: 24px; font-weight: 800; color: white; margin-top: 4px; display: block;">
                <i class="fas fa-check-double" style="color: var(--accent-light); margin-right: 6px;"></i> {{ $stats['submitted_count'] }} Kali
            </span>
        </div>

        <div class="card" style="padding: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Peserta Lulus</span>
            <span style="font-size: 24px; font-weight: 800; color: var(--success); margin-top: 4px; display: block;">
                <i class="fas fa-user-check" style="margin-right: 6px;"></i> {{ $stats['passed_count'] }} (<span style="font-size: 16px;">{{ $stats['pass_rate'] }}%</span>)
            </span>
        </div>

        <div class="card" style="padding: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Belum Lulus</span>
            <span style="font-size: 24px; font-weight: 800; color: var(--danger); margin-top: 4px; display: block;">
                <i class="fas fa-user-times" style="margin-right: 6px;"></i> {{ $stats['failed_count'] }} Siswa
            </span>
        </div>

        <div class="card" style="padding: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Skor Tertinggi</span>
            <span style="font-size: 24px; font-weight: 800; color: var(--success); margin-top: 4px; display: block;">
                <i class="fas fa-arrow-up" style="margin-right: 6px;"></i> {{ $stats['highest_score'] }}
            </span>
        </div>

        <div class="card" style="padding: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Rata-rata Skor</span>
            <span style="font-size: 24px; font-weight: 800; color: var(--warning); margin-top: 4px; display: block;">
                <i class="fas fa-star" style="margin-right: 6px;"></i> {{ $stats['avg_score'] }}
            </span>
        </div>
    </div>

    <!-- Attempts Table -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-users" style="color: var(--accent); margin-right: 8px;"></i> Daftar Percobaan Peserta pada Kuis Ini</h3>
            <span class="badge badge-info">{{ $attempts->count() }} Catatan</span>
        </div>

        @if($attempts->isEmpty())
            <div style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
                Belum ada peserta yang mengerjakan kuis ini.
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Peserta</th>
                            <th>Kontak & Email</th>
                            <th>Waktu Mulai</th>
                            <th>Durasi Pengerjaan</th>
                            <th>Skor Akhir</th>
                            <th>Status Kelulusan</th>
                            <th>Metode Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $att)
                            @php
                                $isPassed = $att->score >= $quiz->passing_score;
                                $duration = $att->started_at && $att->finished_at ? $att->started_at->diffInMinutes($att->finished_at) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: white;">{{ $att->user?->name ?: 'Terhapus' }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">ID: #{{ $att->user_id }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: white;">{{ $att->user?->email ?: '-' }}</div>
                                    @if($att->user?->phone)
                                        <div style="font-size: 11px; color: var(--accent-light);"><i class="fab fa-whatsapp"></i> {{ $att->user->phone }}</div>
                                    @endif
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    {{ $att->started_at ? $att->started_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td style="font-weight: 600; color: var(--warning);">
                                    @if($att->status === 'submitted')
                                        <i class="fas fa-clock"></i> {{ $duration }} Menit
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        <span style="font-size: 18px; font-weight: 800; color: {{ $isPassed ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ $att->score }}
                                        </span>
                                        <span style="font-size: 11px; color: var(--text-muted);">/ 100</span>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        @if($isPassed)
                                            <span class="badge badge-success">LULUS</span>
                                        @else
                                            <span class="badge badge-danger">BELUM LULUS</span>
                                        @endif
                                    @elseif($att->status === 'in_progress')
                                        <span class="badge badge-warning">Berlangsung</span>
                                    @else
                                        <span class="badge badge-danger">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->end_reason === 'time_up')
                                        <span style="color: var(--warning); font-size: 12px;"><i class="fas fa-hourglass-end"></i> Waktu Habis</span>
                                    @elseif($att->end_reason === 'tab_switch' || $att->end_reason === 'force_submit')
                                        <span style="color: var(--danger); font-size: 12px;"><i class="fas fa-shield-alt"></i> Anti-Cheat</span>
                                    @else
                                        <span style="color: var(--success); font-size: 12px;"><i class="fas fa-user-check"></i> Manual</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
