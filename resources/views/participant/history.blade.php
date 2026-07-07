@extends('layouts.participant')

@section('title', 'Riwayat Ujian & Nilai')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(6,182,212,0.15)); border: 1px solid rgba(99,102,241,0.4); padding: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-light);"><i class="fas fa-history"></i> Catatan Akademik</span>
                <h1 style="font-size: 28px; font-weight: 800; color: white; margin-top: 4px;">Riwayat Ujian & Nilai Evaluasi</h1>
                <p style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">
                    Pantau perkembangan belajar dan hasil evaluasi seluruh kuis yang pernah Anda kerjakan di tenant ini.
                </p>
            </div>
            <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenant ?? request()->segment(1)]) }}" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    @php
        $userAttempts = auth('participant')->user()->quizAttempts;
        $submitted = $userAttempts->where('status', 'submitted');
        $avgScore = $submitted->count() > 0 ? round($submitted->avg('score'), 1) : 0;
        $passedCount = $submitted->filter(function($att) {
            return $att->score >= ($att->quiz?->passing_score ?: 70);
        })->count();
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(99,102,241,0.15); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Percobaan</span>
                <span style="font-size: 24px; font-weight: 800; color: white;">{{ $userAttempts->count() }} Kali</span>
            </div>
        </div>

        <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(16,185,129,0.15); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Rata-rata Nilai</span>
                <span style="font-size: 24px; font-weight: 800; color: var(--success);">{{ $avgScore }}</span>
            </div>
        </div>

        <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(6,182,212,0.15); color: var(--accent-light); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fas fa-trophy"></i>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block;">Ujian Lulus</span>
                <span style="font-size: 24px; font-weight: 800; color: white;">{{ $passedCount }} <span style="font-size: 14px; color: var(--text-muted); font-weight: 500;">/ {{ $submitted->count() }}</span></span>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-list-ol" style="color: var(--warning); margin-right: 8px;"></i> Daftar Riwayat Pengerjaan</h3>
            <span class="badge badge-info">{{ $attempts->total() }} Catatan</span>
        </div>

        @if($attempts->isEmpty())
            <div style="padding: 60px 20px; text-align: center;">
                <i class="fas fa-inbox" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 700; color: white;">Belum Ada Riwayat Ujian</h3>
                <p style="font-size: 13px; color: var(--text-muted); max-width: 400px; margin: 8px auto 20px;">
                    Anda belum pernah memulai atau menyelesaikan evaluasi kuis apa pun.
                </p>
                <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenant ?? request()->segment(1)]) }}" class="btn btn-primary">
                    <i class="fas fa-play"></i> Mulai Kuis Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul Kuis</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Skor Akhir</th>
                            <th>Keterangan</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $att)
                            @php
                                $isPassed = $att->score >= ($att->quiz?->passing_score ?: 70);
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: white; font-size: 15px;">
                                        {{ $att->quiz?->title ?: 'Kuis Terhapus' }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        Kategori: {{ $att->quiz?->category ?: 'Umum' }}
                                    </div>
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    {{ $att->created_at->format('d M Y') }}
                                    <div style="font-size: 11px; color: var(--accent-light);">{{ $att->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                                    @elseif($att->status === 'in_progress')
                                        <span class="badge badge-warning"><i class="fas fa-spinner fa-spin"></i> Berlangsung</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-clock"></i> Expired</span>
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
                                            <span class="badge badge-success" style="font-size: 11px;">LULUS</span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 11px;">BELUM LULUS</span>
                                        @endif
                                    @elseif($att->end_reason === 'tab_switch' || $att->end_reason === 'force_submit')
                                        <span class="badge badge-danger" style="font-size: 11px;">Anti-Cheat</span>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if($att->status === 'in_progress')
                                        <a href="{{ route('tenant.participant.quiz.attempt.take', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $att->id]) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-play"></i> Lanjut
                                        </a>
                                    @else
                                        <a href="{{ route('tenant.participant.quiz.attempt.result', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $att->id]) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-eye"></i> Pembahasan
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($attempts->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 13px; color: var(--text-muted);">
                        Menampilkan {{ $attempts->firstItem() }} - {{ $attempts->lastItem() }} dari {{ $attempts->total() }} riwayat
                    </span>
                    <div>
                        {{ $attempts->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
