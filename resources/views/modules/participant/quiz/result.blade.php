@extends('layouts.app-backend')

@section('title', 'Hasil Ujian: ' . $quiz->title)

@section('content')
<div style="max-width: 850px; margin: 0 auto; display: flex; flex-direction: column; gap: 28px;">

    <!-- Celebratory Result Hero Card -->
    <div class="card" style="border-color: {{ $hasPassed ? 'var(--success)' : 'var(--danger)' }}; box-shadow: 0 0 50px {{ $hasPassed ? 'rgba(16,185,129,0.2)' : 'rgba(239,68,68,0.2)' }}; text-align: center; padding: 40px 28px; position: relative; overflow: hidden;">
        
        <div style="width: 84px; height: 84px; border-radius: 50%; background: {{ $hasPassed ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color: {{ $hasPassed ? 'var(--success)' : 'var(--danger)' }}; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; border: 2px solid {{ $hasPassed ? 'var(--success)' : 'var(--danger)' }}; box-shadow: 0 10px 25px {{ $hasPassed ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)' }};">
            <i class="fas {{ $hasPassed ? 'fa-trophy' : 'fa-times-circle' }}"></i>
        </div>

        <span style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: {{ $hasPassed ? 'var(--success)' : 'var(--danger)' }};">
            {{ $hasPassed ? '✨ EVALUASI BERHASIL' : '⚠️ EVALUASI BELUM LULUS' }}
        </span>

        <h1 style="font-size: 32px; font-weight: 800; color: white; margin: 8px 0 12px;">
            {{ $hasPassed ? 'Selamat! Anda Dinyatakan LULUS!' : 'Mohon Maaf, Anda Belum Lulus' }}
        </h1>

        <p style="font-size: 15px; color: var(--text-secondary); max-width: 550px; margin: 0 auto 28px; line-height: 1.6;">
            {{ $hasPassed 
                ? 'Luar biasa! Nilai Anda telah memenuhi kriteria batas kelulusan minimum yang ditetapkan untuk ujian ini.' 
                : 'Jangan berkecil hati. Anda dapat mempelajari kembali materi pembahasan di bawah dan mengulangi ujian apabila kesempatan masih tersedia.' }}
        </p>

        <!-- Score Display Box -->
        <div style="background: var(--bg-input); border: 1px solid var(--border); border-radius: 20px; padding: 24px; max-width: 400px; margin: 0 auto 32px; display: flex; align-items: center; justify-content: space-around;">
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block; text-transform: uppercase;">Skor Akhir Anda</span>
                <span style="font-size: 48px; font-weight: 800; color: {{ $hasPassed ? 'var(--success)' : 'var(--danger)' }}; font-family: 'Outfit', sans-serif; line-height: 1;">
                    {{ $attempt->score }}
                </span>
                <span style="font-size: 14px; color: var(--text-muted);">/ 100</span>
            </div>
            <div style="height: 50px; width: 1px; background: var(--border);"></div>
            <div>
                <span style="font-size: 12px; color: var(--text-muted); display: block; text-transform: uppercase;">Nilai Lulus</span>
                <span style="font-size: 32px; font-weight: 800; color: white; font-family: 'Outfit', sans-serif;">
                    {{ $quiz->passing_score }}%
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenant ?? request()->segment(1)]) }}" class="btn btn-secondary" style="padding: 14px 28px; font-size: 15px;">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>

            @if($remainingAttempts === null || $remainingAttempts > 0)
                <form method="POST" action="{{ route('tenant.participant.quiz.attempt.start', ['tenant' => $tenant ?? request()->segment(1), 'quiz' => $quiz->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="padding: 14px 28px; font-size: 15px; background: linear-gradient(135deg, var(--primary), var(--accent)); font-weight: 800;">
                        <i class="fas fa-redo"></i> Ulangi Ujian Ini (Sisa: {{ $remainingAttempts === null ? '∞' : $remainingAttempts . 'x' }})
                    </button>
                </form>
            @endif
        </div>

    </div>

    <!-- Exam Details & Metrics Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle" style="color: var(--accent); margin-right: 8px;"></i> Rincian Waktu & Percobaan</h3>
        </div>
        <div class="card-body">
            <div class="grid-4">
                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Waktu Mulai</span>
                    <span style="font-size: 14px; font-weight: 700; color: white; margin-top: 4px; display: block;">
                        {{ $attempt->started_at ? $attempt->started_at->format('d M Y, H:i') : '-' }}
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Waktu Selesai</span>
                    <span style="font-size: 14px; font-weight: 700; color: white; margin-top: 4px; display: block;">
                        {{ $attempt->finished_at ? $attempt->finished_at->format('d M Y, H:i') : '-' }}
                    </span>
                </div>

                @php
                    $durationMins = $attempt->started_at && $attempt->finished_at ? $attempt->started_at->diffInMinutes($attempt->finished_at) : 0;
                @endphp
                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Durasi Pengerjaan</span>
                    <span style="font-size: 14px; font-weight: 700; color: var(--warning); margin-top: 4px; display: block;">
                        <i class="fas fa-clock" style="margin-right: 4px;"></i> {{ $durationMins }} Menit
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 16px; border-radius: 12px; border: 1px solid var(--border);">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Metode Selesai</span>
                    <span style="font-size: 14px; font-weight: 700; color: white; margin-top: 4px; display: block;">
                        @if($attempt->end_reason === 'time_up')
                            <span style="color: var(--warning);"><i class="fas fa-hourglass-end"></i> Waktu Habis</span>
                        @elseif($attempt->end_reason === 'tab_switch' || $attempt->end_reason === 'force_submit')
                            <span style="color: var(--danger);"><i class="fas fa-shield-alt"></i> Anti-Cheat Paksa</span>
                        @else
                            <span style="color: var(--success);"><i class="fas fa-user-check"></i> Manual / Mandiri</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions & AI Explanation Review -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-list-check" style="color: var(--primary-light); margin-right: 8px;"></i> Pembahasan Soal & Kunci Jawaban</h3>
            <span class="badge badge-info">{{ $attempt->answers->count() }} dari {{ $quiz->questions()->count() }} Dijawab</span>
        </div>

        <div style="display: flex; flex-direction: column; divide-y: 1px solid var(--border);">
            @foreach($quiz->questions as $index => $q)
                @php
                    $userAnswer = $attempt->answers->where('question_id', $q->id)->first();
                    $isCorrect = $userAnswer?->is_correct;
                @endphp

                <div style="padding: 24px; {{ $loop->notFirst ? 'border-top: 1px solid var(--border);' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
                        <div style="display: flex; gap: 12px; align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $isCorrect ? 'var(--success)' : ($userAnswer ? 'var(--danger)' : 'var(--text-muted)') }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 700; color: white; line-height: 1.5;">
                                    {{ $q->question_text }}
                                </h4>
                                <div style="display: flex; gap: 10px; margin-top: 6px;">
                                    <span class="badge badge-info" style="font-size: 11px;">Bobot: {{ $q->points }} Poin</span>
                                    @if($isCorrect)
                                        <span class="badge badge-success" style="font-size: 11px;"><i class="fas fa-check"></i> Benar</span>
                                    @elseif($userAnswer)
                                        <span class="badge badge-danger" style="font-size: 11px;"><i class="fas fa-times"></i> Salah</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 11px;"><i class="fas fa-minus"></i> Tidak Dijawab</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options List -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px; margin-left: 44px; margin-bottom: 16px;">
                        @foreach($q->options as $optIdx => $opt)
                            @php
                                $letter = chr(65 + $optIdx);
                                $isUserChoice = $userAnswer?->selected_option_id == $opt->id;
                            @endphp
                            <div style="padding: 12px 16px; border-radius: 10px; border: 1px solid {{ $opt->is_correct ? 'var(--success)' : ($isUserChoice ? 'var(--danger)' : 'var(--border)') }}; background: {{ $opt->is_correct ? 'rgba(16,185,129,0.15)' : ($isUserChoice ? 'rgba(239,68,68,0.15)' : 'var(--bg-input)') }}; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: {{ $opt->is_correct ? 'var(--success)' : ($isUserChoice ? 'var(--danger)' : 'var(--text-white)') }}; font-weight: {{ $opt->is_correct || $isUserChoice ? '700' : '400' }};">
                                    <span style="font-weight: 800; color: var(--text-muted);">{{ $letter }}.</span>
                                    <span>{{ $opt->option_text }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($isUserChoice)
                                        <span class="badge {{ $opt->is_correct ? 'badge-success' : 'badge-danger' }}" style="font-size: 10px;">Jawaban Anda</span>
                                    @endif
                                    @if($opt->is_correct)
                                        <span style="color: var(--success); font-size: 16px;" title="Kunci Jawaban Benar">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- AI Explanation Box -->
                    @if($q->explanation)
                        <div style="margin-left: 44px; background: rgba(99,102,241,0.08); border-left: 3px solid var(--primary-light); padding: 14px 16px; border-radius: 0 8px 8px 0;">
                            <div style="font-size: 12px; font-weight: 700; color: var(--primary-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                <i class="fas fa-brain"></i> Pembahasan AI / Penjelasan Ilmiah:
                            </div>
                            <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                                {{ $q->explanation }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
