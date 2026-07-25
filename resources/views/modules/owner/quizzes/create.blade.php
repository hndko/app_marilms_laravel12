@extends('layouts.app-backend')

@section('title', 'Buat Kuis Baru')

@section('content')
<div x-data="{ 
    activeTab: 'ai',
    questionCount: 5,
    tokenPerQuestion: {{ $tokenPerQuestion }},
    showLoadingOverlay: false,
    get tokenCost() { return this.questionCount * this.tokenPerQuestion; }
}" class="max-w-4xl mx-auto space-y-6">

    <!-- Mode Selector Tabs -->
    <div class="p-1.5 rounded-2xl bg-gray-100 border border-gray-200 grid grid-cols-2 gap-2">
        <button type="button" @click="activeTab = 'ai'"
            :class="activeTab === 'ai' ? 'bg-white text-brand-600 font-bold shadow-2xs' : 'text-gray-600 hover:text-gray-900 font-semibold'"
            class="py-3 px-4 rounded-xl text-xs transition flex items-center justify-center gap-2">
            <i class="fas fa-wand-magic-sparkles text-sm"></i>
            <span>Generate Otomatis dengan AI</span>
        </button>
        <button type="button" @click="activeTab = 'manual'"
            :class="activeTab === 'manual' ? 'bg-white text-brand-600 font-bold shadow-2xs' : 'text-gray-600 hover:text-gray-900 font-semibold'"
            class="py-3 px-4 rounded-xl text-xs transition flex items-center justify-center gap-2">
            <i class="fas fa-pen-to-square text-sm"></i>
            <span>Buat Manual Tanpa AI</span>
        </button>
    </div>

    <!-- TAB 1: AI GENERATOR FORM -->
    <div x-show="activeTab === 'ai'" class="space-y-6">
        <div class="rounded-2xl border border-brand-200 bg-white p-6 sm:p-8 shadow-theme-xs space-y-6">
            
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center font-bold text-xl shrink-0">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">AI Quiz Generator</h3>
                    <p class="text-xs text-gray-500">Biarkan AI merancang soal kuis beserta pilihan jawaban dan pembahasannya dalam hitungan detik.</p>
                </div>
            </div>

            <!-- Token Cost Estimator Banner -->
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                <div class="flex items-center gap-3">
                    <i class="fas fa-coins text-amber-600 text-2xl shrink-0"></i>
                    <div>
                        <div class="font-bold text-amber-950">
                            Estimasi Konsumsi Token: <span x-text="tokenCost" class="text-amber-700 font-extrabold text-sm">5</span> Token
                        </div>
                        <p class="text-amber-800">
                            Saldo Anda saat ini: <strong>{{ $isUnlimited ? '∞ Unlimited' : number_format($tokenBalance) . ' Token' }}</strong>
                        </p>
                    </div>
                </div>
                @if(!$isUnlimited && $tokenBalance < 5)
                    <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" 
                        class="px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-2xs transition shrink-0 flex items-center gap-1.5">
                        <i class="fas fa-plus-circle text-xs"></i>
                        <span>Top Up Token</span>
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('tenant.owner.quizzes.generate', ['tenant' => $tenant]) }}" id="ai-generate-form" onsubmit="showLoadingState()" class="space-y-5">
                @csrf
                
                <!-- Topic / Materi -->
                <div class="space-y-1.5">
                    <label for="topic" class="block text-xs font-bold text-gray-700">
                        Topik atau Materi Pembelajaran <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-book-bookmark text-xs"></i>
                        </span>
                        <input type="text" name="topic" id="topic" value="{{ old('topic') }}" required
                            placeholder="Contoh: Sejarah Proklamasi Kemerdekaan Indonesia / Dasar Aljabar Linier..."
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                    </div>
                    <p class="text-[10px] text-gray-400">Tuliskan topik secara spesifik agar soal yang dihasilkan oleh AI lebih presisi.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label for="category" class="block text-xs font-bold text-gray-700">
                            Kategori <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-tag text-xs"></i>
                            </span>
                            <input type="text" name="category" id="category" value="{{ old('category', 'Umum') }}" required
                                placeholder="Contoh: Sejarah, Matematika, Teknologi..."
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>

                    <!-- Tingkat Kesulitan -->
                    <div class="space-y-1.5">
                        <label for="difficulty" class="block text-xs font-bold text-gray-700">
                            Tingkat Kesulitan <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-layer-group text-xs"></i>
                            </span>
                            <select name="difficulty" id="difficulty" required
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">
                                <option value="sedang" {{ old('difficulty') === 'sedang' ? 'selected' : '' }}>Sedang (Standard)</option>
                                <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah (Dasar)</option>
                                <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit (HOTS / Analitis)</option>
                                <option value="campuran" {{ old('difficulty') === 'campuran' ? 'selected' : '' }}>Campuran (Mudah, Sedang & Sulit)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- Jumlah Soal -->
                    <div class="space-y-1.5">
                        <label for="question_count" class="block text-xs font-bold text-gray-700">
                            Jumlah Soal <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-list-ol text-xs"></i>
                            </span>
                            <input type="number" name="question_count" id="question_count" x-model.number="questionCount" required min="1" max="30"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>

                    <!-- Tipe Soal -->
                    <div class="space-y-1.5">
                        <label for="question_type" class="block text-xs font-bold text-gray-700">
                            Tipe Soal <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-tasks text-xs"></i>
                            </span>
                            <select name="question_type" id="question_type" required
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">
                                <option value="multiple_choice" {{ old('question_type') === 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                <option value="true_false" {{ old('question_type') === 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                            </select>
                        </div>
                    </div>

                    <!-- Jumlah Opsi -->
                    <div class="space-y-1.5">
                        <label for="option_count" class="block text-xs font-bold text-gray-700">
                            Opsi per Soal <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-bars-staggered text-xs"></i>
                            </span>
                            <select name="option_count" id="option_count" required
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">
                                <option value="4" {{ old('option_count', 4) == 4 ? 'selected' : '' }}>4 Opsi (A, B, C, D)</option>
                                <option value="5" {{ old('option_count') == 5 ? 'selected' : '' }}>5 Opsi (A, B, C, D, E)</option>
                                <option value="3" {{ old('option_count') == 3 ? 'selected' : '' }}>3 Opsi (A, B, C)</option>
                                <option value="2" {{ old('option_count') == 2 ? 'selected' : '' }}>2 Opsi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Durasi Pengerjaan -->
                    <div class="space-y-1.5">
                        <label for="time_limit" class="block text-xs font-bold text-gray-700">
                            Durasi Pengerjaan (Menit) <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-clock text-xs"></i>
                            </span>
                            <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit', 30) }}" required min="5" max="300"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>

                    <!-- Passing Score -->
                    <div class="space-y-1.5">
                        <label for="passing_score" class="block text-xs font-bold text-gray-700">
                            Passing Score / Nilai Lulus (%) <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-trophy text-xs"></i>
                            </span>
                            <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', 70) }}" required min="10" max="100"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>
                </div>

                <!-- Instructions Textarea -->
                <div class="space-y-1.5">
                    <label for="instructions" class="block text-xs font-bold text-gray-700">
                        Instruksi Khusus untuk AI (Opsional)
                    </label>
                    <textarea name="instructions" id="instructions" rows="3"
                        placeholder="Contoh: Fokus pada analisis peristiwa tahun 1945, hindari pertanyaan tentang tanggal lahir tokoh..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 p-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">{{ old('instructions') }}</textarea>
                </div>

                <!-- Card Footer Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
                        class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs transition">
                        Batal
                    </a>
                    <button type="submit" id="ai-submit-btn" 
                        class="px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                        <i class="fas fa-bolt text-xs"></i>
                        <span>Generate Soal Sekarang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: MANUAL CREATION FORM -->
    <div x-show="activeTab === 'manual'" class="space-y-6" style="display: none;">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-theme-xs space-y-6">
            
            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center font-bold text-xl shrink-0">
                    <i class="fas fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Buat Kuis Manual</h3>
                    <p class="text-xs text-gray-500">Buat kerangka kuis baru dan tambahkan butir soal satu per satu secara manual.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.owner.quizzes.store', ['tenant' => $tenant]) }}" class="space-y-5">
                @csrf
                <input type="hidden" name="status" value="draft" />

                <!-- Judul Kuis -->
                <div class="space-y-1.5">
                    <label for="manual_title" class="block text-xs font-bold text-gray-700">
                        Judul Kuis <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-heading text-xs"></i>
                        </span>
                        <input type="text" name="title" id="manual_title" required
                            placeholder="Contoh: Ujian Tengah Semester - Aljabar..."
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label for="manual_category" class="block text-xs font-bold text-gray-700">
                            Kategori <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-tag text-xs"></i>
                            </span>
                            <input type="text" name="category" id="manual_category" required value="Umum"
                                placeholder="Contoh: Matematika..."
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>

                    <!-- Durasi -->
                    <div class="space-y-1.5">
                        <label for="manual_time_limit" class="block text-xs font-bold text-gray-700">
                            Durasi (Menit) <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-clock text-xs"></i>
                            </span>
                            <input type="number" name="time_limit" id="manual_time_limit" value="60" required min="5" max="300"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>

                    <!-- Passing Score -->
                    <div class="space-y-1.5">
                        <label for="manual_passing_score" class="block text-xs font-bold text-gray-700">
                            Passing Score (%) <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-trophy text-xs"></i>
                            </span>
                            <input type="number" name="passing_score" id="manual_passing_score" value="70" required min="10" max="100"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-1.5">
                    <label for="manual_description" class="block text-xs font-bold text-gray-700">
                        Deskripsi / Petunjuk Kuis
                    </label>
                    <textarea name="description" id="manual_description" rows="3"
                        placeholder="Tuliskan petunjuk pengerjaan kuis untuk peserta..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 p-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs"></textarea>
                </div>

                <!-- Card Footer Action Buttons -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
                        class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs transition">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                        <i class="fas fa-save text-xs"></i>
                        <span>Simpan & Lanjut ke Editor Soal</span>
                    </button>
                </div>
            </form>
        </div>
    <!-- Full Screen Teleport Loading Overlay for AI Generation -->
    <template x-teleport="body">
        <div x-show="showLoadingOverlay" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[999999] flex flex-col items-center justify-center p-6 bg-gray-900/80 backdrop-blur-md text-white text-center space-y-4"
            style="display: none;">
            <div class="w-16 h-16 rounded-full border-4 border-white/20 border-t-brand-400 border-r-amber-400 animate-spin"></div>
            <div class="space-y-2">
                <h2 class="text-xl font-extrabold tracking-tight">✨ AI Sedang Merancang Soal...</h2>
                <p class="text-xs text-gray-300 max-w-sm mx-auto leading-relaxed">
                    Mohon tunggu beberapa saat. AI sedang menyusun butir pertanyaan, pilihan jawaban, dan penjelasan ilmiah yang relevan.
                </p>
            </div>
        </div>
    </template>

</div>

<script>
    function showLoadingState() {
        const root = document.querySelector('[x-data]');
        if (root && root._x_dataStack) {
            root._x_dataStack[0].showLoadingOverlay = true;
        }
        const btn = document.getElementById('ai-submit-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Memproses AI...';
        }
    }
</script>
@endsection
