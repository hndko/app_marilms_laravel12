@extends('layouts.app-backend')

@section('title', $quiz->title)

@section('content')
<div class="space-y-6">

    <!-- Quiz Overview Header Card -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-theme-xs space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                        {{ $quiz->category ?: 'Umum' }}
                    </span>
                    @if($quiz->status === 'active')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-success-50 text-success-700 border border-success-200">
                            <i class="fas fa-check-circle mr-1"></i> Aktif & Siap Dikerjakan
                        </span>
                    @elseif($quiz->status === 'draft')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <i class="fas fa-edit mr-1"></i> Draft (Belum Dipublikasi)
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-error-50 text-error-700 border border-error-200">
                            <i class="fas fa-archive mr-1"></i> Diarsipkan
                        </span>
                    @endif
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-snug">
                    {{ $quiz->title }}
                </h2>

                @if($quiz->description)
                    <p class="text-xs text-gray-500 leading-relaxed max-w-3xl">
                        {{ $quiz->description }}
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
                    class="px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs transition">
                    <i class="fas fa-arrow-left text-xs mr-1.5"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id]) }}" 
                    class="px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                    <i class="fas fa-pen-to-square text-xs"></i>
                    <span>Edit Kuis & Soal</span>
                </a>
            </div>
        </div>

        <!-- Specs Grid -->
        <div class="pt-6 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-1">
                <span class="text-[10px] font-semibold text-gray-400 uppercase">Total Butir Soal</span>
                <p class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-list-ol text-brand-500 text-sm"></i>
                    <span>{{ $quiz->questions->count() }} Soal</span>
                </p>
            </div>

            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-1">
                <span class="text-[10px] font-semibold text-gray-400 uppercase">Durasi Waktu</span>
                <p class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                    <span>{{ $quiz->time_limit }} Menit</span>
                </p>
            </div>

            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-1">
                <span class="text-[10px] font-semibold text-gray-400 uppercase">Nilai Kelulusan</span>
                <p class="text-lg font-extrabold text-success-600 flex items-center gap-2">
                    <i class="fas fa-trophy text-sm"></i>
                    <span>{{ $quiz->passing_score }}%</span>
                </p>
            </div>

            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-1">
                <span class="text-[10px] font-semibold text-gray-400 uppercase">Batas Percobaan</span>
                <p class="text-lg font-extrabold text-indigo-600 flex items-center gap-2">
                    <i class="fas fa-rotate text-sm"></i>
                    <span>{{ $quiz->max_attempts }}x Percobaan</span>
                </p>
            </div>
        </div>

    </div>

    <!-- Questions Preview List -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-theme-xs space-y-6">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-file-lines text-brand-500"></i>
                    <span>Daftar Butir Soal & Pilihan Jawaban</span>
                </h3>
                <p class="text-xs text-gray-500">Pratinjau soal kuis dan kunci jawaban yang akan dikerjakan oleh peserta.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-brand-50 text-brand-700 border border-brand-200">
                {{ $quiz->questions->count() }} Soal
            </span>
        </div>

        @if($quiz->questions->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h4 class="text-base font-bold text-gray-900">Kuis Ini Belum Memiliki Soal</h4>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">
                    Silakan masuk ke mode editor untuk menambahkan soal secara manual atau menggunakan generator AI.
                </p>
                <a href="{{ route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id]) }}" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Tambah Soal Sekarang</span>
                </a>
            </div>
        @else
            <div class="space-y-6 divide-y divide-gray-100">
                @foreach($quiz->questions as $index => $q)
                    <div class="pt-6 first:pt-0 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-brand-500 text-white font-extrabold text-xs flex items-center justify-center shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-900 leading-snug">
                                        {{ $q->question_text }}
                                    </h4>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                            Bobot: {{ $q->points }} Poin
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200 capitalize">
                                            Tipe: {{ str_replace('_', ' ', $q->question_type) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Options List Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-11">
                            @foreach($q->options as $optIdx => $opt)
                                @php
                                    $letter = chr(65 + $optIdx);
                                @endphp
                                <div class="p-3 rounded-xl border flex items-center justify-between gap-3 text-xs transition {{ $opt->is_correct ? 'bg-success-50/60 border-success-300 text-success-800 font-bold' : 'bg-gray-50/50 border-gray-200 text-gray-700' }}">
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-extrabold w-4 text-center text-gray-500">{{ $letter }}.</span>
                                        <span>{{ $opt->option_text }}</span>
                                    </div>
                                    @if($opt->is_correct)
                                        <span class="text-success-600 text-sm" title="Kunci Jawaban Benar">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Explanation Box -->
                        @if($q->explanation)
                            <div class="ml-11 p-3.5 rounded-r-xl rounded-l-none border-l-4 border-brand-500 bg-brand-50/50 space-y-1 text-xs">
                                <div class="font-bold text-brand-700 uppercase tracking-wider text-[10px] flex items-center gap-1.5">
                                    <i class="fas fa-lightbulb"></i>
                                    <span>Pembahasan / Penjelasan Jawaban:</span>
                                </div>
                                <p class="text-gray-600 leading-relaxed">
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
