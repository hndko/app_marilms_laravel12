@extends('layouts.app-backend')

@section('title', 'Edit Kuis: ' . $quiz->title)

@section('content')
<form method="POST" action="{{ route('tenant.owner.quizzes.update', ['quiz' => $quiz->id]) }}" id="quiz-editor-form" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Sticky Top Header Action Bar -->
    <div class="sticky top-20 z-30 bg-white/95 backdrop-blur-md border border-gray-200 rounded-2xl p-4 sm:p-5 shadow-theme-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-brand-600">Mode Editor Soal Kuis</span>
            <h3 class="text-base font-bold text-gray-900 truncate max-w-md">{{ $quiz->title }}</h3>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}" 
                class="px-3.5 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs transition flex items-center gap-1.5">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Batal / Preview</span>
            </a>
            <button type="button" onclick="addQuestion()" 
                class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 font-bold text-xs transition flex items-center gap-1.5">
                <i class="fas fa-plus text-xs"></i>
                <span>Tambah Soal Baru</span>
            </button>
            <button type="submit" 
                class="px-5 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-1.5">
                <i class="fas fa-save text-xs"></i>
                <span>Simpan Semua Perubahan</span>
            </button>
        </div>
    </div>

    <!-- Section 1: General Quiz Settings Card -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-5">
        <div class="border-b border-gray-100 pb-3">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-gear text-brand-500"></i>
                <span>Pengaturan Umum Kuis</span>
            </h3>
            <p class="text-xs text-gray-500">Kelola judul, durasi, passing score, dan status publikasi kuis.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Judul Kuis -->
            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-gray-700">
                    Judul Kuis <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-heading text-xs"></i>
                    </span>
                    <input type="text" name="title" id="title" value="{{ old('title', $quiz->title) }}" required
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                </div>
            </div>

            <!-- Kategori -->
            <div class="space-y-1.5">
                <label for="category" class="block text-xs font-bold text-gray-700">
                    Kategori <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-tag text-xs"></i>
                    </span>
                    <input type="text" name="category" id="category" value="{{ old('category', $quiz->category) }}" required
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
            <!-- Durasi -->
            <div class="space-y-1.5">
                <label for="time_limit" class="block text-xs font-bold text-gray-700">
                    Durasi (Menit) <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-clock text-xs"></i>
                    </span>
                    <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" required min="5" max="300"
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                </div>
            </div>

            <!-- Passing Score -->
            <div class="space-y-1.5">
                <label for="passing_score" class="block text-xs font-bold text-gray-700">
                    Passing Score (%) <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-trophy text-xs"></i>
                    </span>
                    <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" required min="10" max="100"
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                </div>
            </div>

            <!-- Batas Percobaan -->
            <div class="space-y-1.5">
                <label for="max_attempts" class="block text-xs font-bold text-gray-700">
                    Batas Percobaan <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-rotate text-xs"></i>
                    </span>
                    <input type="number" name="max_attempts" id="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" required min="1" max="10"
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                </div>
            </div>

            <!-- Status Kuis -->
            <div class="space-y-1.5">
                <label for="status" class="block text-xs font-bold text-gray-700">
                    Status Kuis <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-toggle-on text-xs"></i>
                    </span>
                    <select name="status" id="status" required
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">
                        <option value="draft" {{ old('status', $quiz->status) === 'draft' ? 'selected' : '' }}>Draft (Belum Dipublish)</option>
                        <option value="active" {{ old('status', $quiz->status) === 'active' ? 'selected' : '' }}>Aktif (Publik)</option>
                        <option value="archived" {{ old('status', $quiz->status) === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Deskripsi Kuis -->
        <div class="space-y-1.5">
            <label for="description" class="block text-xs font-bold text-gray-700">
                Deskripsi / Petunjuk Pengerjaan
            </label>
            <textarea name="description" id="description" rows="3"
                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 p-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">{{ old('description', $quiz->description) }}</textarea>
        </div>
    </div>

    <!-- Section 2: Questions List Editor Card -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-5">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-list-ol text-brand-500"></i>
                    <span>Daftar Butir Soal & Kunci Jawaban</span>
                </h3>
                <p class="text-xs text-gray-500">Kelola soal, poin, opsi jawaban, dan penjelasan jawaban ilmiah.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200" id="q-count-badge">
                0 Soal
            </span>
        </div>

        <div id="questions-container" class="space-y-4">
            <!-- Dynamically populated via JS in Light Mode -->
        </div>

        <div class="pt-4 border-t border-dashed border-gray-200 text-center">
            <button type="button" onclick="addQuestion()" 
                class="px-5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold text-xs transition inline-flex items-center gap-2 shadow-2xs">
                <i class="fas fa-plus-circle text-xs text-brand-500"></i>
                <span>Tambah Butir Soal Baru</span>
            </button>
        </div>
    </div>

</form>

<script>
let questionsData = @json($quiz->questions);
let qIndex = 0;

function initEditor() {
    const container = document.getElementById('questions-container');
    container.innerHTML = '';
    qIndex = 0;

    if (questionsData && questionsData.length > 0) {
        questionsData.forEach((q) => {
            renderQuestionCard(q);
        });
    } else {
        addQuestion();
    }
    updateBadge();
}

function renderQuestionCard(q = {}) {
    const container = document.getElementById('questions-container');
    const idx = qIndex++;
    const qId = q.id || 'new';
    const qText = q.question_text || '';
    const qPoints = q.points || 10;
    const qExp = q.explanation || '';
    const qOptions = q.options || [
        { option_text: 'Pilihan A', is_correct: 1 },
        { option_text: 'Pilihan B', is_correct: 0 },
        { option_text: 'Pilihan C', is_correct: 0 },
        { option_text: 'Pilihan D', is_correct: 0 }
    ];

    const card = document.createElement('div');
    card.className = 'p-5 rounded-2xl border border-gray-200 bg-gray-50/50 space-y-4 relative shadow-2xs';
    card.id = `q-card-${idx}`;

    let optionsHtml = '';
    qOptions.forEach((opt, oIdx) => {
        const letter = String.fromCharCode(65 + oIdx);
        const isChecked = opt.is_correct ? 'checked' : '';
        optionsHtml += `
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white border border-gray-200 shadow-2xs" id="q-${idx}-opt-${oIdx}">
                <input type="radio" name="questions[${idx}][correct_option]" value="${oIdx}" ${isChecked} title="Jadikan Kunci Jawaban Benar" class="w-4 h-4 text-success-600 focus:ring-success-500 cursor-pointer">
                <input type="hidden" name="questions[${idx}][options][${oIdx}][is_correct]" class="opt-hidden-correct" value="${opt.is_correct ? 1 : 0}">
                <span class="font-extrabold text-gray-500 text-xs w-4 text-center">${letter}.</span>
                <input type="text" name="questions[${idx}][options][${oIdx}][option_text]" class="h-9 flex-1 rounded-lg border border-gray-200 bg-gray-50/30 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition" value="${opt.option_text}" required placeholder="Tuliskan teks pilihan jawaban...">
                <button type="button" onclick="removeOption(${idx}, ${oIdx})" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-error-600 hover:bg-error-50 transition" title="Hapus Opsi"><i class="fas fa-times text-xs"></i></button>
            </div>
        `;
    });

    card.innerHTML = `
        <input type="hidden" name="questions[${idx}][id]" value="${qId}">
        
        <div class="flex items-center justify-between pb-3 border-b border-gray-200/80">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-brand-50 text-brand-700 border border-brand-200">Soal #${idx + 1}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-gray-600">Bobot Poin:</label>
                    <input type="number" name="questions[${idx}][points]" value="${qPoints}" class="h-8 w-16 rounded-lg border border-gray-200 bg-white px-2 text-xs font-bold text-center text-gray-800 focus:border-brand-500 focus:outline-none" min="1" max="100" required>
                </div>
                <button type="button" onclick="removeQuestion(${idx})" class="px-2.5 py-1.5 rounded-lg bg-error-50 hover:bg-error-100 text-error-600 border border-error-200 font-bold text-xs transition flex items-center gap-1">
                    <i class="fas fa-trash text-xs"></i>
                    <span>Hapus Soal</span>
                </button>
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-gray-700">Teks Pertanyaan Soal <span class="text-error-500">*</span></label>
            <textarea name="questions[${idx}][question_text]" class="w-full rounded-xl border border-gray-200 bg-white p-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:outline-none transition" rows="2" required placeholder="Tuliskan pertanyaan soal secara eksplisit...">${qText}</textarea>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-bold text-gray-700">Pilihan Jawaban (Pilih radio button untuk kunci jawaban benar) <span class="text-error-500">*</span></label>
                <button type="button" onclick="addOption(${idx})" class="px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 font-bold text-[11px] transition flex items-center gap-1">
                    <i class="fas fa-plus text-[10px]"></i>
                    <span>Tambah Opsi</span>
                </button>
            </div>
            <div id="q-${idx}-options-container" class="space-y-2">
                ${optionsHtml}
            </div>
        </div>

        <div class="space-y-1.5 pt-2">
            <label class="block text-xs font-bold text-brand-600 flex items-center gap-1.5">
                <i class="fas fa-lightbulb"></i>
                <span>Pembahasan / Penjelasan Jawaban (Opsional)</span>
            </label>
            <input type="text" name="questions[${idx}][explanation]" class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:outline-none transition" value="${qExp}" placeholder="Jelaskan mengapa kunci jawaban tersebut benar...">
        </div>
    `;

    container.appendChild(card);
    updateBadge();
    bindRadioEvents(idx);
}

function bindRadioEvents(qIdx) {
    const card = document.getElementById(`q-card-${qIdx}`);
    if (!card) return;
    const radios = card.querySelectorAll(`input[type="radio"][name="questions[${qIdx}][correct_option]"]`);
    radios.forEach((radio) => {
        radio.addEventListener('change', function() {
            const allHidden = card.querySelectorAll('.opt-hidden-correct');
            allHidden.forEach(h => h.value = "0");
            const selectedIdx = this.value;
            const hiddenTarget = card.querySelector(`input[name="questions[${qIdx}][options][${selectedIdx}][is_correct]"]`);
            if (hiddenTarget) hiddenTarget.value = "1";
        });
    });
}

function addQuestion() {
    renderQuestionCard();
}

function removeQuestion(idx) {
    if (confirm('Apakah Anda yakin ingin menghapus butir soal ini?')) {
        const card = document.getElementById(`q-card-${idx}`);
        if (card) card.remove();
        updateBadge();
    }
}

function addOption(qIdx) {
    const container = document.getElementById(`q-${qIdx}-options-container`);
    if (!container) return;
    const currentCount = container.children.length;
    if (currentCount >= 5) {
        alert('Maksimal 5 opsi pilihan jawaban per soal.');
        return;
    }
    const letter = String.fromCharCode(65 + currentCount);
    const div = document.createElement('div');
    div.className = 'flex items-center gap-3 p-2.5 rounded-xl bg-white border border-gray-200 shadow-2xs';
    div.id = `q-${qIdx}-opt-${currentCount}`;
    div.innerHTML = `
        <input type="radio" name="questions[${qIdx}][correct_option]" value="${currentCount}" title="Jadikan Kunci Jawaban Benar" class="w-4 h-4 text-success-600 focus:ring-success-500 cursor-pointer">
        <input type="hidden" name="questions[${qIdx}][options][${currentCount}][is_correct]" class="opt-hidden-correct" value="0">
        <span class="font-extrabold text-gray-500 text-xs w-4 text-center">${letter}.</span>
        <input type="text" name="questions[${qIdx}][options][${currentCount}][option_text]" class="h-9 flex-1 rounded-lg border border-gray-200 bg-gray-50/30 px-3 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition" required placeholder="Tuliskan teks pilihan jawaban...">
        <button type="button" onclick="removeOption(${qIdx}, ${currentCount})" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-error-600 hover:bg-error-50 transition" title="Hapus Opsi"><i class="fas fa-times text-xs"></i></button>
    `;
    container.appendChild(div);
    bindRadioEvents(qIdx);
}

function removeOption(qIdx, oIdx) {
    const optRow = document.getElementById(`q-${qIdx}-opt-${oIdx}`);
    if (optRow) {
        const container = document.getElementById(`q-${qIdx}-options-container`);
        if (container && container.children.length <= 2) {
            alert('Minimal harus ada 2 opsi pilihan jawaban.');
            return;
        }
        optRow.remove();
    }
}

function updateBadge() {
    const count = document.querySelectorAll('#questions-container > div').length;
    const badge = document.getElementById('q-count-badge');
    if (badge) badge.innerText = `${count} Soal`;
}

document.addEventListener('DOMContentLoaded', function() {
    initEditor();
});
</script>
@endsection
