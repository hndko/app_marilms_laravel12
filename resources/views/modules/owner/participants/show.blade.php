@extends('layouts.app-backend')

@section('title', 'Detail Peserta: ' . $participant->name)
@section('page-title', 'Profil & Riwayat Peserta')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}">Data Peserta</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>{{ Str::limit($participant->name, 20) }}</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Participant Profile Card -->
    <div class="card" style="border-color: rgba(6,182,212,0.4); box-shadow: 0 0 30px rgba(6,182,212,0.1);">
        <div class="card-body" style="padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 72px; height: 72px; border-radius: 20px; background: linear-gradient(135deg, var(--accent), var(--primary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 32px; box-shadow: 0 10px 25px rgba(6,182,212,0.3);">
                        {{ strtoupper(substr($participant->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                            <h2 style="font-size: 24px; font-weight: 800; color: white;">{{ $participant->name }}</h2>
                            @if($participant->status === 'active')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-ban"></i> Nonaktif</span>
                            @endif
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px; color: var(--text-muted);">
                            <span><i class="fas fa-envelope" style="color: var(--accent); margin-right: 6px;"></i> {{ $participant->email }}</span>
                            @if($participant->phone)
                                <span><i class="fab fa-whatsapp" style="color: var(--success); margin-right: 6px;"></i> {{ $participant->phone }}</span>
                            @endif
                            <span><i class="fas fa-calendar-alt" style="color: var(--warning); margin-right: 6px;"></i> Daftar: {{ $participant->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('tenant.owner.participants.edit', ['tenant' => $tenant, 'participant' => $participant->id]) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                    <form method="POST" action="{{ route('tenant.owner.participants.reset-password', ['tenant' => $tenant, 'participant' => $participant->id]) }}" onsubmit="return confirm('Reset password peserta ini menjadi password123?')">
                        @csrf
                        <button type="submit" class="btn btn-warning" title="Reset Password ke password123">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Metrics -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border);">
                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Ujian Dikerjakan</span>
                    <span style="font-size: 22px; font-weight: 800; color: var(--text-white); margin-top: 4px; display: block;">
                        <i class="fas fa-clipboard-list" style="color: var(--primary-light); margin-right: 6px;"></i> {{ $participant->quizAttempts->count() }} Kali
                    </span>
                </div>

                @php
                    $completedAttempts = $participant->quizAttempts->where('status', 'submitted');
                    $avgScore = $completedAttempts->count() > 0 ? round($completedAttempts->avg('score'), 1) : 0;
                @endphp

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Rata-rata Nilai</span>
                    <span style="font-size: 22px; font-weight: 800; color: var(--success); margin-top: 4px; display: block;">
                        <i class="fas fa-star" style="margin-right: 6px;"></i> {{ $avgScore }}
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Ujian Selesai</span>
                    <span style="font-size: 22px; font-weight: 800; color: var(--accent-light); margin-top: 4px; display: block;">
                        <i class="fas fa-check-double" style="margin-right: 6px;"></i> {{ $completedAttempts->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Attempts History Table -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-history" style="color: var(--warning); margin-right: 8px;"></i> Riwayat Pengerjaan Kuis</h3>
            <span class="badge badge-primary">{{ $participant->quizAttempts->count() }} Percobaan</span>
        </div>

        @if($participant->quizAttempts->isEmpty())
            <div style="padding: 50px 20px; text-align: center;">
                <i class="fas fa-clock" style="font-size: 40px; color: var(--text-muted); margin-bottom: 12px;"></i>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-white);">Belum Ada Riwayat Ujian</h4>
                <p style="font-size: 13px; color: var(--text-muted);">Peserta ini belum pernah memulai atau menyelesaikan ujian kuis apa pun.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul Kuis</th>
                            <th>Tanggal Mulai</th>
                            <th>Status Pengerjaan</th>
                            <th>Waktu Selesai</th>
                            <th>Skor Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participant->quizAttempts as $attempt)
                            <tr>
                                <td>
                                    <a href="{{ route('tenant.owner.quizzes.show', ['tenant' => $tenant, 'quiz' => $attempt->quiz_id]) }}" style="font-weight: 700; color: var(--text-white); text-decoration: none;">
                                        {{ $attempt->quiz?->title ?: 'Kuis Terhapus' }}
                                    </a>
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    {{ $attempt->started_at ? $attempt->started_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    @if($attempt->status === 'submitted')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                                    @elseif($attempt->status === 'in_progress')
                                        <span class="badge badge-info"><i class="fas fa-spinner fa-spin"></i> Sedang Mengerjakan</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-clock"></i> Waktu Habis / Expired</span>
                                    @endif
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    {{ $attempt->finished_at ? $attempt->finished_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    @if($attempt->status === 'submitted')
                                        <span style="font-size: 16px; font-weight: 800; color: {{ $attempt->score >= ($attempt->quiz?->passing_score ?: 70) ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ $attempt->score }}
                                        </span>
                                        <span style="font-size: 11px; color: var(--text-muted);">/ 100</span>
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
