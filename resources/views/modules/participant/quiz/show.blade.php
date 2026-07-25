@extends('layouts.app-backend')

@section('title', $quiz->title . ' — Petunjuk Ujian')

@section('content')
<div style="max-width: 850px; margin: 0 auto; display: flex; flex-direction: column; gap: 28px;">

    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted);">
        <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenant ?? request()->segment(1)]) }}" style="color: var(--accent-light); text-decoration: none;">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
        <span style="color: white;">{{ Str::limit($quiz->title, 30) }}</span>
    </div>

    <!-- Main Card -->
    <div class="card" style="border-color: rgba(99,102,241,0.5); box-shadow: 0 0 40px rgba(99,102,241,0.15);">
        
        <div class="card-header" style="background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(6,182,212,0.15)); padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; width: 100%;">
                <div>
                    <span class="badge badge-info" style="margin-bottom: 10px;">{{ $quiz->category ?: 'Umum' }}</span>
                    <h1 style="font-size: 28px; font-weight: 800; color: white; line-height: 1.3;">{{ $quiz->title }}</h1>
                    @if($quiz->description)
                        <p style="font-size: 14px; color: var(--text-secondary); margin-top: 8px; line-height: 1.6;">
                            {{ $quiz->description }}
                        </p>
                    @endif
                </div>

                @if($hasPassed)
                    <div style="background: rgba(16,185,129,0.2); border: 1px solid var(--success); padding: 10px 16px; border-radius: 12px; display: flex; align-items: center; gap: 8px; color: var(--success); font-weight: 700;">
                        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                        <span>Anda Sudah Lulus Kuis Ini</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body" style="padding: 28px; display: flex; flex-direction: column; gap: 28px;">
            
            <!-- Specs Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Soal</span>
                    <span style="font-size: 20px; font-weight: 800; color: white; margin-top: 4px; display: block;">
                        <i class="fas fa-list-ol" style="color: var(--accent); margin-right: 6px;"></i> {{ $quiz->questions()->count() }} Soal
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Durasi Waktu</span>
                    <span style="font-size: 20px; font-weight: 800; color: white; margin-top: 4px; display: block;">
                        <i class="fas fa-stopwatch" style="color: var(--warning); margin-right: 6px;"></i> {{ $quiz->time_limit }} Menit
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Nilai Kelulusan</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--success); margin-top: 4px; display: block;">
                        <i class="fas fa-trophy" style="margin-right: 6px;"></i> {{ $quiz->passing_score }}%
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Sisa Kesempatan</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--primary-light); margin-top: 4px; display: block;">
                        <i class="fas fa-redo" style="margin-right: 6px;"></i> {{ $remainingAttempts === null ? '∞ Unlimited' : $remainingAttempts . ' Kali' }}
                    </span>
                </div>
            </div>

            <!-- Anti-Cheat & Rules Box -->
            <div style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.3); border-radius: 16px; padding: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: var(--warning); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-shield-alt" style="font-size: 20px;"></i> Kebijakan Anti-Cheat & Petunjuk Pengerjaan
                </h3>
                <ul style="font-size: 13px; color: var(--text-secondary); line-height: 1.7; padding-left: 20px; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                    <li><strong>Timer Server-Authoritative:</strong> Waktu ujian berjalan mundur dari server secara akurat. Segera kumpulkan jawaban sebelum waktu habis.</li>
                    <li><strong>Deteksi Aktivitas Tab (Anti-Cheat):</strong> Sistem memantau jika Anda berpindah tab atau meminimalkan jendela browser. Pelanggaran berulang dapat menyebabkan ujian dikumpulkan secara paksa otomatis.</li>
                    <li><strong>Auto-Save Jawaban:</strong> Setiap kali Anda memilih opsi jawaban, sistem otomatis menyimpannya ke server. Anda tidak perlu khawatir kehilangan jawaban jika terjadi gangguan koneksi sesaat.</li>
                    <li><strong>Koreksi Otomatis:</strong> Hasil nilai dan pembahasan (jika diizinkan pengajar) akan langsung ditampilkan setelah ujian selesai.</li>
                </ul>
            </div>

            <!-- Start Action Button -->
            <div style="text-align: center; padding-top: 10px;">
                @if($remainingAttempts !== null && $remainingAttempts <= 0)
                    <button disabled class="btn btn-secondary" style="padding: 16px 36px; font-size: 16px; font-weight: 700; opacity: 0.5; cursor: not-allowed; width: 100%; justify-content: center;">
                        <i class="fas fa-lock"></i> Kesempatan Mengerjakan Telah Habis
                    </button>
                @else
                    <form method="POST" action="{{ route('tenant.participant.quiz.attempt.start', ['tenant' => $tenant ?? request()->segment(1), 'quiz' => $quiz->id]) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 16px 36px; font-size: 16px; font-weight: 800; width: 100%; justify-content: center; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 10px 25px rgba(99,102,241,0.4);">
                            <i class="fas fa-rocket"></i> Saya Paham Aturan & Mulai Mengerjakan Sekarang
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

    <!-- Previous Attempts History -->
    @if($previousAttempts->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-history" style="color: var(--accent); margin-right: 8px;"></i> Riwayat Percobaan Sebelumnya</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Percobaan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Skor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previousAttempts as $idx => $att)
                            <tr>
                                <td style="font-weight: 700; color: white;">Percobaan #{{ $previousAttempts->count() - $idx }}</td>
                                <td style="font-size: 13px; color: var(--text-muted);">{{ $att->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($att->status === 'submitted')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                                    @elseif($att->status === 'in_progress')
                                        <span class="badge badge-warning"><i class="fas fa-spinner fa-spin"></i> Sedang Mengerjakan</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-clock"></i> Expired</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->status === 'submitted')
                                        <span style="font-size: 15px; font-weight: 800; color: {{ $att->score >= $quiz->passing_score ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ $att->score }}
                                        </span>
                                        <span style="font-size: 11px; color: var(--text-muted);">/ 100</span>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($att->status === 'in_progress')
                                        <a href="{{ route('tenant.participant.quiz.attempt.take', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $att->id]) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-play"></i> Lanjutkan
                                        </a>
                                    @else
                                        <a href="{{ route('tenant.participant.quiz.attempt.result', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $att->id]) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-eye"></i> Lihat Hasil
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
