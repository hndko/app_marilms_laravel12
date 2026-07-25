@extends('layouts.owner')

@section('title', $quiz->title)
@section('page-title', 'Preview & Detail Kuis')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.quizzes.index') }}">Daftar Kuis</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>{{ Str::limit($quiz->title, 25) }}</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Quiz Overview Header Card -->
    <div class="card" style="border-color: rgba(99,102,241,0.4); box-shadow: 0 0 30px rgba(99,102,241,0.1);">
        <div class="card-body" style="padding: 28px;">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <span class="badge badge-info">{{ $quiz->category ?: 'Umum' }}</span>
                        @if($quiz->status === 'active')
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif & Siap Dikerjakan</span>
                        @elseif($quiz->status === 'draft')
                            <span class="badge badge-warning"><i class="fas fa-edit"></i> Draft (Belum Dipublikasi)</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-archive"></i> Diarsipkan</span>
                        @endif
                    </div>
                    <h2 style="font-size: 28px; font-weight: 800; color: var(--text-white);">{{ $quiz->title }}</h2>
                    @if($quiz->description)
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 8px; max-width: 700px; line-height: 1.6;">
                            {{ $quiz->description }}
                        </p>
                    @endif
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id]) }}" class="btn btn-primary" style="padding: 12px 20px;">
                        <i class="fas fa-edit"></i> Edit Kuis & Soal
                    </a>
                </div>
            </div>

            <!-- Specs Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; padding-top: 20px; border-top: 1px solid var(--border);">
                <div style="background: var(--bg-input); padding: 14px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Butir Soal</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--text-white); margin-top: 4px; display: block;">
                        <i class="fas fa-list-ol" style="color: var(--accent); margin-right: 6px;"></i> {{ $quiz->questions->count() }} Soal
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 14px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Durasi Waktu</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--text-white); margin-top: 4px; display: block;">
                        <i class="fas fa-stopwatch" style="color: var(--warning); margin-right: 6px;"></i> {{ $quiz->time_limit }} Menit
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 14px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Nilai Kelulusan</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--success); margin-top: 4px; display: block;">
                        <i class="fas fa-trophy" style="margin-right: 6px;"></i> {{ $quiz->passing_score }}%
                    </span>
                </div>

                <div style="background: var(--bg-input); padding: 14px; border-radius: 12px;">
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Batas Percobaan</span>
                    <span style="font-size: 20px; font-weight: 800; color: var(--primary-light); margin-top: 4px; display: block;">
                        <i class="fas fa-redo" style="margin-right: 6px;"></i> {{ $quiz->max_attempts }}x
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Questions Preview List -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-file-alt" style="color: var(--accent); margin-right: 8px;"></i> Daftar Butir Soal & Pilihan Jawaban</h3>
            <span class="badge badge-primary">{{ $quiz->questions->count() }} Soal</span>
        </div>

        @if($quiz->questions->isEmpty())
            <div style="padding: 60px 20px; text-align: center;">
                <i class="fas fa-question-circle" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-white);">Kuis Ini Belum Memiliki Soal</h4>
                <p style="font-size: 13px; color: var(--text-muted); margin: 6px 0 20px;">Silakan masuk ke editor untuk menambahkan soal secara manual atau menggunakan AI.</p>
                <a href="{{ route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal Sekarang
                </a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; divide-y: 1px solid var(--border);">
                @foreach($quiz->questions as $index => $q)
                    <div style="padding: 24px; {{ $loop->notFirst ? 'border-top: 1px solid var(--border);' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 style="font-size: 16px; font-weight: 700; color: var(--text-white); line-height: 1.5;">
                                        {{ $q->question_text }}
                                    </h4>
                                    <div style="display: flex; gap: 10px; margin-top: 6px;">
                                        <span class="badge badge-info" style="font-size: 11px;">Bobot: {{ $q->points }} Poin</span>
                                        <span class="badge badge-secondary" style="font-size: 11px;">Tipe: {{ str_replace('_', ' ', $q->question_type) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Options List -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px; margin-left: 44px; margin-bottom: 16px;">
                            @foreach($q->options as $optIdx => $opt)
                                @php
                                    $letter = chr(65 + $optIdx); // A, B, C, D...
                                @endphp
                                <div style="padding: 12px 16px; border-radius: 10px; border: 1px solid {{ $opt->is_correct ? 'var(--success)' : 'var(--border)' }}; background: {{ $opt->is_correct ? 'rgba(16,185,129,0.1)' : 'var(--bg-input)' }}; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: {{ $opt->is_correct ? 'var(--success)' : 'var(--text-white)' }}; font-weight: {{ $opt->is_correct ? '700' : '400' }};">
                                        <span style="font-weight: 800; color: var(--text-muted);">{{ $letter }}.</span>
                                        <span>{{ $opt->option_text }}</span>
                                    </div>
                                    @if($opt->is_correct)
                                        <span style="color: var(--success); font-size: 16px;" title="Jawaban Benar">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Explanation Box -->
                        @if($q->explanation)
                            <div style="margin-left: 44px; background: rgba(99,102,241,0.08); border-left: 3px solid var(--primary-light); padding: 12px 16px; border-radius: 0 8px 8px 0;">
                                <div style="font-size: 12px; font-weight: 700; color: var(--primary-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                    <i class="fas fa-lightbulb"></i> Pembahasan / Penjelasan:
                                </div>
                                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                                    {{ $q->explanation }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>
@endsection
