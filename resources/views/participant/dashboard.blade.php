@extends('layouts.participant')

@section('title', 'Beranda Ujian')

@section('content')
<div style="display: flex; flex-direction: column; gap: 32px;">

    <!-- Welcome Hero Card -->
    <div class="card" style="background: linear-gradient(135deg, rgba(6,182,212,0.2), rgba(99,102,241,0.2)); border: 1px solid rgba(6,182,212,0.4); padding: 32px; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2; max-width: 650px;">
            <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-light);"><i class="fas fa-sparkles"></i> Portal Evaluasi AI</span>
            <h1 style="font-size: 32px; font-weight: 800; color: white; margin: 8px 0 12px; line-height: 1.3;">
                Selamat Datang, {{ auth('participant')->user()->name }}!
            </h1>
            <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 24px;">
                Pilih kuis atau ujian yang tersedia di bawah ini. Pastikan koneksi internet stabil dan kerjakan dengan jujur tanpa membuka tab atau aplikasi lain selama ujian berlangsung.
            </p>
            <div style="display: flex; gap: 12px;">
                <a href="#available-quizzes" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; background: linear-gradient(135deg, var(--accent), var(--primary));">
                    <i class="fas fa-play"></i> Lihat Daftar Kuis Tersedia
                </a>
                <a href="{{ route('tenant.participant.history', ['tenant' => $tenant ?? request()->segment(1)]) }}" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px;">
                    <i class="fas fa-history"></i> Riwayat & Nilai
                </a>
            </div>
        </div>
        <i class="fas fa-user-graduate" style="position: absolute; right: -20px; bottom: -30px; font-size: 220px; color: rgba(255,255,255,0.04); z-index: 1; transform: rotate(-10deg);"></i>
    </div>

    <!-- Available Quizzes Section -->
    <div id="available-quizzes">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: white;"><i class="fas fa-clipboard-list" style="color: var(--accent); margin-right: 10px;"></i> Kuis & Ujian Tersedia</h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Daftar paket evaluasi aktif dalam tenant ini</p>
            </div>
            <span class="badge badge-info">{{ $quizzes->count() }} Kuis Aktif</span>
        </div>

        @if($quizzes->isEmpty())
            <div class="card" style="padding: 60px 20px; text-align: center;">
                <div style="width: 72px; height: 72px; border-radius: 20px; background: rgba(99,102,241,0.1); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: white;">Belum Ada Kuis yang Aktif</h3>
                <p style="font-size: 14px; color: var(--text-muted); max-width: 450px; margin: 8px auto 0;">
                    Saat ini belum ada paket ujian yang dibuka oleh pengajar. Silakan cek kembali beberapa saat lagi atau hubungi admin tenant Anda.
                </p>
            </div>
        @else
            <div class="grid-3">
                @foreach($quizzes as $quiz)
                    @php
                        $user = auth('participant')->user();
                        $remaining = $quiz->remainingAttempts($user);
                        $hasPassed = $quiz->hasUserPassed($user);
                        $inProgressAttempt = $quiz->attempts()->where('user_id', $user->id)->inProgress()->first();
                    @endphp

                    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s; border-color: {{ $inProgressAttempt ? 'var(--warning)' : ($hasPassed ? 'rgba(16,185,129,0.4)' : 'var(--border)') }};">
                        
                        <div class="card-body" style="padding: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 14px;">
                                <span class="badge badge-info" style="font-size: 11px;">{{ $quiz->category ?: 'Umum' }}</span>
                                @if($inProgressAttempt)
                                    <span class="badge badge-warning"><i class="fas fa-spinner fa-spin"></i> Sedang Mengerjakan</span>
                                @elseif($hasPassed)
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lulus</span>
                                @elseif($remaining !== null && $remaining <= 0)
                                    <span class="badge badge-danger"><i class="fas fa-lock"></i> Habis Kesempatan</span>
                                @else
                                    <span class="badge badge-primary"><i class="fas fa-play"></i> Tersedia</span>
                                @endif
                            </div>

                            <h3 style="font-size: 18px; font-weight: 700; color: white; line-height: 1.4; margin-bottom: 8px;">
                                {{ $quiz->title }}
                            </h3>

                            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
                                {{ $quiz->description ?: 'Tidak ada deskripsi khusus untuk kuis ini.' }}
                            </p>

                            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border); display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 12px;">
                                <div>
                                    <span style="color: var(--text-muted); display: block;">Jumlah Soal:</span>
                                    <span style="font-weight: 700; color: white; font-size: 14px;">
                                        <i class="fas fa-list-ol" style="color: var(--accent); margin-right: 4px;"></i> {{ $quiz->questions_count }} Soal
                                    </span>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted); display: block;">Durasi:</span>
                                    <span style="font-weight: 700; color: white; font-size: 14px;">
                                        <i class="fas fa-stopwatch" style="color: var(--warning); margin-right: 4px;"></i> {{ $quiz->time_limit }} Menit
                                    </span>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted); display: block;">Nilai Lulus:</span>
                                    <span style="font-weight: 700; color: var(--success); font-size: 14px;">
                                        <i class="fas fa-trophy" style="margin-right: 4px;"></i> {{ $quiz->passing_score }}%
                                    </span>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted); display: block;">Kesempatan:</span>
                                    <span style="font-weight: 700; color: var(--primary-light); font-size: 14px;">
                                        <i class="fas fa-redo" style="margin-right: 4px;"></i> {{ $remaining === null ? '∞ Unlimited' : $remaining . ' Kali' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer" style="background: rgba(0,0,0,0.15); padding: 16px 20px;">
                            @if($inProgressAttempt)
                                <a href="{{ route('tenant.participant.quiz.attempt.take', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $inProgressAttempt->id]) }}" class="btn btn-warning" style="width: 100%; justify-content: center; font-weight: 700;">
                                    <i class="fas fa-play-circle"></i> Lanjutkan Pengerjaan
                                </a>
                            @elseif($remaining !== null && $remaining <= 0)
                                <button disabled class="btn btn-secondary" style="width: 100%; justify-content: center; opacity: 0.5; cursor: not-allowed;">
                                    <i class="fas fa-lock"></i> Batas Habis
                                </button>
                            @else
                                <a href="{{ route('tenant.participant.quiz.show', ['tenant' => $tenant ?? request()->segment(1), 'quiz' => $quiz->id]) }}" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700; background: linear-gradient(135deg, var(--primary), var(--accent));">
                                    <i class="fas fa-arrow-right"></i> Lihat Petunjuk & Mulai
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
