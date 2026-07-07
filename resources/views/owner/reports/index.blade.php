@extends('layouts.owner')

@section('title', 'Laporan & Analitik')
@section('page-title', 'Analitik & Laporan')

@section('breadcrumb')
    <span>Laporan</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(6,182,212,0.15)); border: 1px solid rgba(99,102,241,0.4); padding: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-light);"><i class="fas fa-chart-line"></i> Analitik Tenant</span>
                <h1 style="font-size: 28px; font-weight: 800; color: white; margin-top: 4px;">Pusat Laporan & Evaluasi Pembelajaran</h1>
                <p style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">
                    Pantau statistik pengerjaan ujian, tingkat kelulusan siswa, dan ekspor data hasil ke file CSV/Excel.
                </p>
            </div>
            <a href="{{ route('tenant.owner.reports.export', ['type' => 'csv']) }}" class="btn btn-primary" style="padding: 14px 24px; font-size: 14px; background: linear-gradient(135deg, var(--success), #059669);">
                <i class="fas fa-file-csv"></i> Ekspor Semua Data (CSV)
            </a>
        </div>
    </div>

    <!-- Overall Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="card" style="padding: 22px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(99,102,241,0.15); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Kuis Dibuat</span>
                <span style="font-size: 26px; font-weight: 800; color: white;">{{ $totalQuizzes }} Kuis</span>
            </div>
        </div>

        <div class="card" style="padding: 22px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(6,182,212,0.15); color: var(--accent-light); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Peserta / Siswa</span>
                <span style="font-size: 26px; font-weight: 800; color: white;">{{ $totalParticipants }} Siswa</span>
            </div>
        </div>

        <div class="card" style="padding: 22px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(245,158,11,0.15); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                <i class="fas fa-stopwatch"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Percobaan Ujian</span>
                <span style="font-size: 26px; font-weight: 800; color: white;">{{ $totalAttempts }} Kali</span>
            </div>
        </div>

        <div class="card" style="padding: 22px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(16,185,129,0.15); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                <i class="fas fa-trophy"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Tingkat Kelulusan</span>
                <span style="font-size: 26px; font-weight: 800; color: var(--success);">{{ $passRate }}%</span>
            </div>
        </div>
    </div>

    <!-- Top Quizzes Section -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-star" style="color: var(--warning); margin-right: 8px;"></i> Kuis Terpopuler & Paling Banyak Dikerjakan</h3>
            <span class="badge badge-primary">Top 5 Kuis</span>
        </div>

        @if($topQuizzes->isEmpty())
            <div style="padding: 40px 20px; text-align: center; color: var(--text-muted);">
                Belum ada data pengerjaan kuis yang tercatat.
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul Kuis</th>
                            <th>Kategori</th>
                            <th>Passing Score</th>
                            <th>Total Percobaan</th>
                            <th style="text-align: right;">Analitik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topQuizzes as $q)
                            <tr>
                                <td style="font-weight: 700; color: white;">{{ $q->title }}</td>
                                <td><span class="badge badge-info">{{ $q->category ?: 'Umum' }}</span></td>
                                <td style="color: var(--success); font-weight: 700;">{{ $q->passing_score }}%</td>
                                <td>
                                    <span class="badge badge-secondary" style="font-size: 13px; font-weight: 700;">
                                        {{ $q->attempts_count }} Kali
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('tenant.owner.reports.quiz', ['quiz' => $q->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-chart-bar"></i> Lihat Laporan Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recent Attempts Section -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-clock" style="color: var(--accent); margin-right: 8px;"></i> Aktivitas Pengerjaan Ujian Terbaru</h3>
            <span class="badge badge-info">15 Catatan Terakhir</span>
        </div>

        @if($recentAttempts->isEmpty())
            <div style="padding: 40px 20px; text-align: center; color: var(--text-muted);">
                Belum ada riwayat ujian terbaru.
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Judul Kuis</th>
                            <th>Waktu Mulai</th>
                            <th>Status</th>
                            <th>Skor</th>
                            <th>Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAttempts as $att)
                            @php
                                $isPassed = $att->score >= ($att->quiz?->passing_score ?: 70);
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: white;">{{ $att->user?->name ?: 'Terhapus' }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $att->user?->email ?: '-' }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('tenant.owner.reports.quiz', ['quiz' => $att->quiz_id]) }}" style="font-weight: 600; color: var(--accent-light); text-decoration: none;">
                                        {{ $att->quiz?->title ?: 'Terhapus' }}
                                    </a>
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    {{ $att->started_at ? $att->started_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif($att->status === 'in_progress')
                                        <span class="badge badge-warning">Berlangsung</span>
                                    @else
                                        <span class="badge badge-danger">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        <span style="font-size: 16px; font-weight: 800; color: {{ $isPassed ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ $att->score }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        @if($isPassed)
                                            <span class="badge badge-success" style="font-size: 11px;">LULUS</span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 11px;">BELUM LULUS</span>
                                        @endif
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
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
