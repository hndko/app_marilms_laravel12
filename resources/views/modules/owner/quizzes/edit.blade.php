@extends('layouts.owner')

@section('title', 'Edit Kuis: ' . $quiz->title)
@section('page-title', 'Editor Kuis & Butir Soal')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.quizzes.index') }}">Daftar Kuis</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}">{{ Str::limit($quiz->title, 20) }}</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Edit</span>
@endsection

@section('content')
<form method="POST" action="{{ route('tenant.owner.quizzes.update', ['quiz' => $quiz->id]) }}" id="quiz-editor-form">
    @csrf
    @method('PUT')

    <div style="display: flex; flex-direction: column; gap: 28px;">

        <!-- Top Action Sticky Bar -->
        <div class="card" style="position: sticky; top: 20px; z-index: 100; background: rgba(30,35,48,0.95); backdrop-filter: blur(10px); border: 1px solid var(--primary-light); padding: 16px 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--accent-light);">Mode Editor</span>
                    <h3 style="font-size: 18px; font-weight: 800; color: white; margin-top: 2px;">{{ $quiz->title }}</h3>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal / Preview
                    </a>
                    <button type="button" onclick="addQuestion()" class="btn btn-accent">
                        <i class="fas fa-plus"></i> Tambah Soal Baru
                    </button>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px; background: linear-gradient(135deg, var(--primary), var(--accent));">
                        <i class="fas fa-save"></i> Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </div>

        <!-- Section 1: General Quiz Settings -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-cog" style="color: var(--primary); margin-right: 8px;"></i> Pengaturan Umum Kuis</h3>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Judul Kuis <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="title" class="form-input" required value="{{ old('title', $quiz->title) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="category" class="form-input" required value="{{ old('category', $quiz->category) }}">
                    </div>
                </div>

                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Durasi (Menit) <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="time_limit" class="form-input" required min="5" max="300" value="{{ old('time_limit', $quiz->time_limit) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Passing Score (%) <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="passing_score" class="form-input" required min="10" max="100" value="{{ old('passing_score', $quiz->passing_score) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Percobaan <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="max_attempts" class="form-input" required min="1" max="10" value="{{ old('max_attempts', $quiz->max_attempts) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Kuis <span style="color: var(--danger);">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ old('status', $quiz->status) === 'draft' ? 'selected' : '' }}>Draft (Belum Dipublish)</option>
                            <option value="active" {{ old('status', $quiz->status) === 'active' ? 'selected' : '' }}>Aktif (Publik)</option>
                            <option value="archived" {{ old('status', $quiz->status) === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi / Petunjuk Pengerjaan</label>
                    <textarea name="description" class="form-input" rows="3">{{ old('description', $quiz->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Questions Editor Container -->
        <div class="card">
            <div class="card-header" style="justify-content: space-between;">
                <h3><i class="fas fa-list-ol" style="color: var(--accent); margin-right: 8px;"></i> Daftar Butir Soal</h3>
                <span class="badge badge-primary" id="q-count-badge">0 Soal</span>
            </div>
            
            <div id="questions-container" style="display: flex; flex-direction: column; gap: 20px; padding: 24px;">
                <!-- Dynamically populated via JS -->
            </div>

            <div style="padding: 20px 24px; border-top: 1px dashed var(--border); text-align: center;">
                <button type="button" onclick="addQuestion()" class="btn btn-secondary" style="padding: 12px 28px;">
                    <i class="fas fa-plus-circle"></i> Tambah Butir Soal Baru
                </button>
            </div>
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
    card.className = 'card';
    card.style.background = 'var(--bg-input)';
    card.style.border = '1px solid var(--border)';
    card.style.padding = '20px';
    card.style.position = 'relative';
    card.id = `q-card-${idx}`;

    let optionsHtml = '';
    qOptions.forEach((opt, oIdx) => {
        const letter = String.fromCharCode(65 + oIdx);
        const isChecked = opt.is_correct ? 'checked' : '';
        optionsHtml += `
            <div class="option-row" id="q-${idx}-opt-${oIdx}" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; background: rgba(0,0,0,0.2); padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border);">
                <input type="radio" name="questions[${idx}][correct_option]" value="${oIdx}" ${isChecked} title="Jadikan Jawaban Benar" style="width: 18px; height: 18px; accent-color: var(--success); cursor: pointer;">
                <input type="hidden" name="questions[${idx}][options][${oIdx}][is_correct]" class="opt-hidden-correct" value="${opt.is_correct ? 1 : 0}">
                <span style="font-weight: 800; color: var(--text-muted); width: 20px;">${letter}.</span>
                <input type="text" name="questions[${idx}][options][${oIdx}][option_text]" class="form-input" value="${opt.option_text}" required placeholder="Tuliskan pilihan jawaban..." style="flex: 1; height: 38px; font-size: 14px;">
                <button type="button" onclick="removeOption(${idx}, ${oIdx})" class="btn btn-sm btn-icon btn-ghost" style="color: var(--danger);" title="Hapus Opsi"><i class="fas fa-times"></i></button>
            </div>
        `;
    });

    card.innerHTML = `
        <input type="hidden" name="questions[${idx}][id]" value="${qId}">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="badge badge-primary" style="font-size: 13px; padding: 6px 12px;">Soal #${idx + 1}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <label style="font-size: 12px; color: var(--text-muted);">Bobot Poin:</label>
                    <input type="number" name="questions[${idx}][points]" value="${qPoints}" class="form-input" style="width: 70px; height: 32px; padding: 4px 8px; font-size: 13px;" min="1" max="100" required>
                </div>
                <button type="button" onclick="removeQuestion(${idx})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus Soal</button>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="font-size: 13px;">Teks Pertanyaan <span style="color: var(--danger);">*</span></label>
            <textarea name="questions[${idx}][question_text]" class="form-input" rows="2" required placeholder="Tuliskan pertanyaan soal secara jelas...">${qText}</textarea>
        </div>

        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <label class="form-label" style="font-size: 13px; margin: 0;">Pilihan Jawaban (Pilih radio button untuk kunci jawaban benar) <span style="color: var(--danger);">*</span></label>
                <button type="button" onclick="addOption(${idx})" class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Tambah Opsi</button>
            </div>
            <div id="q-${idx}-options-container">
                ${optionsHtml}
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" style="font-size: 12px; color: var(--primary-light);"><i class="fas fa-lightbulb"></i> Pembahasan / Penjelasan Jawaban (Opsional)</label>
            <input type="text" name="questions[${idx}][explanation]" class="form-input" value="${qExp}" placeholder="Jelaskan mengapa jawaban tersebut benar...">
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
    div.className = 'option-row';
    div.id = `q-${qIdx}-opt-${currentCount}`;
    div.style = 'display: flex; align-items: center; gap: 10px; margin-bottom: 10px; background: rgba(0,0,0,0.2); padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border);';
    div.innerHTML = `
        <input type="radio" name="questions[${qIdx}][correct_option]" value="${currentCount}" title="Jadikan Jawaban Benar" style="width: 18px; height: 18px; accent-color: var(--success); cursor: pointer;">
        <input type="hidden" name="questions[${qIdx}][options][${currentCount}][is_correct]" class="opt-hidden-correct" value="0">
        <span style="font-weight: 800; color: var(--text-muted); width: 20px;">${letter}.</span>
        <input type="text" name="questions[${qIdx}][options][${currentCount}][option_text]" class="form-input" required placeholder="Tuliskan pilihan jawaban..." style="flex: 1; height: 38px; font-size: 14px;">
        <button type="button" onclick="removeOption(${qIdx}, ${currentCount})" class="btn btn-sm btn-icon btn-ghost" style="color: var(--danger);" title="Hapus Opsi"><i class="fas fa-times"></i></button>
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
    const count = document.querySelectorAll('#questions-container .card').length;
    const badge = document.getElementById('q-count-badge');
    if (badge) badge.innerText = `${count} Soal`;
}

document.addEventListener('DOMContentLoaded', function() {
    initEditor();
});
</script>
@endsection
