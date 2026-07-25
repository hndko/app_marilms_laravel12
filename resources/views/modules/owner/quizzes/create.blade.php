@extends('layouts.app-backend')

@section('title', 'Buat Kuis Baru')
@section('page-title', 'Buat Kuis Baru')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}">Daftar Kuis</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Buat Kuis</span>
@endsection

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- Mode Selector Tabs -->
    <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
        <button type="button" id="tab-ai-btn" class="btn btn-primary" onclick="switchTab('ai')" style="flex: 1; justify-content: center; padding: 14px; font-size: 15px;">
            <i class="fas fa-wand-magic-sparkles"></i> ✨ Generate Otomatis dengan AI
        </button>
        <button type="button" id="tab-manual-btn" class="btn btn-secondary" onclick="switchTab('manual')" style="flex: 1; justify-content: center; padding: 14px; font-size: 15px;">
            <i class="fas fa-edit"></i> 📝 Buat Manual Tanpa AI
        </button>
    </div>

    <!-- TAB 1: AI GENERATOR FORM -->
    <div id="tab-ai-content">
        <div class="card" style="border-color: rgba(99,102,241,0.5); box-shadow: 0 0 30px rgba(99,102,241,0.15);">
            <div class="card-header" style="background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(6,182,212,0.1));">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white);">AI Quiz Generator</h3>
                        <p style="font-size: 13px; color: var(--text-muted);">Biarkan AI merancang soal kuis beserta pilihan jawaban dan pembahasannya dalam detik.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.owner.quizzes.generate', ['tenant' => $tenant]) }}" id="ai-generate-form" onsubmit="showLoadingState()">
                @csrf
                <div class="card-body">
                    
                    <!-- Token Cost Estimator Alert -->
                    <div style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.3); border-radius: 12px; padding: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-coins" style="font-size: 24px; color: var(--warning);"></i>
                            <div>
                                <div style="font-size: 13px; font-weight: 600; color: var(--text-white);">Estimasi Konsumsi Token: <span id="token-cost-display" style="color: var(--warning); font-size: 16px; font-weight: 800;">5</span> Token</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Saldo Anda saat ini: <strong>{{ $isUnlimited ? '∞ Unlimited' : number_format($tokenBalance) . ' Token' }}</strong></div>
                            </div>
                        </div>
                        @if(!$isUnlimited && $tokenBalance < 5)
                            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenant]) }}" class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Top Up Token</a>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Topik atau Materi Pembelajaran <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="topic" class="form-input" required placeholder="Contoh: Sejarah Proklamasi Kemerdekaan Indonesia / Dasar Aljabar Linier" value="{{ old('topic') }}" style="font-size: 15px; padding: 14px;">
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Tuliskan topik se-spesifik mungkin agar soal yang dihasilkan lebih relevan.</p>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="category" class="form-input" required placeholder="Contoh: Sejarah, Matematika, Teknologi" value="{{ old('category', 'Umum') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tingkat Kesulitan <span style="color: var(--danger);">*</span></label>
                            <select name="difficulty" class="form-select" required>
                                <option value="sedang" {{ old('difficulty') === 'sedang' ? 'selected' : '' }}>Sedang (Standard)</option>
                                <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah (Dasar)</option>
                                <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit (HOTS / Analitis)</option>
                                <option value="campuran" {{ old('difficulty') === 'campuran' ? 'selected' : '' }}>Campuran (Mudah, Sedang & Sulit)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Jumlah Soal <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="question_count" id="question_count_input" class="form-input" required min="1" max="30" value="{{ old('question_count', 5) }}" oninput="updateTokenCost()">
                            <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Maksimal 30 soal per proses</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tipe Soal <span style="color: var(--danger);">*</span></label>
                            <select name="question_type" class="form-select" required>
                                <option value="multiple_choice" {{ old('question_type') === 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                <option value="true_false" {{ old('question_type') === 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jumlah Opsi per Soal <span style="color: var(--danger);">*</span></label>
                            <select name="option_count" class="form-select" required>
                                <option value="4" {{ old('option_count', 4) == 4 ? 'selected' : '' }}>4 Opsi (A, B, C, D)</option>
                                <option value="5" {{ old('option_count') == 5 ? 'selected' : '' }}>5 Opsi (A, B, C, D, E)</option>
                                <option value="3" {{ old('option_count') == 3 ? 'selected' : '' }}>3 Opsi (A, B, C)</option>
                                <option value="2" {{ old('option_count') == 2 ? 'selected' : '' }}>2 Opsi</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Durasi Pengerjaan (Menit) <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="time_limit" class="form-input" required min="5" max="300" value="{{ old('time_limit', 30) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nilai Lulus / Passing Score (%) <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="passing_score" class="form-input" required min="10" max="100" value="{{ old('passing_score', 70) }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Instruksi Tambahan untuk AI (Opsional)</label>
                        <textarea name="instructions" class="form-input" rows="3" placeholder="Contoh: Fokus pada analisis peristiwa tahun 1945, hindari pertanyaan tentang tanggal lahir tokoh, gunakan gaya bahasa kasus nyata...">{{ old('instructions') }}</textarea>
                    </div>

                </div>

                <div class="card-footer" style="justify-content: flex-end; gap: 12px; background: rgba(0,0,0,0.15);">
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" id="ai-submit-btn" class="btn btn-primary" style="padding: 14px 28px; font-size: 15px; background: linear-gradient(135deg, var(--primary), var(--accent));">
                        <i class="fas fa-bolt"></i> Generate Soal Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: MANUAL CREATION FORM -->
    <div id="tab-manual-content" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-edit" style="color: var(--primary); margin-right: 8px;"></i> Buat Kuis Manual</h3>
            </div>

            <form method="POST" action="{{ route('tenant.owner.quizzes.store', ['tenant' => $tenant]) }}">
                @csrf
                <input type="hidden" name="status" value="draft">
                
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Judul Kuis <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="title" class="form-input" required placeholder="Contoh: Ujian Tengah Semester - Aljabar">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="category" class="form-input" required placeholder="Contoh: Matematika">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Durasi (Menit) <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="time_limit" class="form-input" required min="5" max="300" value="60">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Lulus / Passing Score (%) <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="passing_score" class="form-input" required min="10" max="100" value="70">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Deskripsi Kuis</label>
                        <textarea name="description" class="form-input" rows="4" placeholder="Tuliskan petunjuk pengerjaan kuis..."></textarea>
                    </div>
                </div>

                <div class="card-footer" style="justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan & Lanjut ke Editor Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Full Screen Loading Overlay for AI Generation -->
<div id="ai-loading-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15,17,23,0.9); backdrop-filter: blur(10px); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 20px;">
    <div style="width: 80px; height: 80px; border-radius: 50%; border: 4px solid rgba(99,102,241,0.2); border-top-color: var(--primary); border-right-color: var(--accent); animation: spin 1s linear infinite; margin-bottom: 24px;"></div>
    <h2 style="font-size: 24px; font-weight: 800; color: white; margin-bottom: 8px;">✨ AI Sedang Merancang Soal...</h2>
    <p style="font-size: 15px; color: var(--text-muted); max-width: 450px; line-height: 1.5;">
        Mohon tunggu beberapa saat. Sistem sedang menyusun pertanyaan, opsi jawaban, dan penjelasan ilmiah yang relevan dengan topik Anda.
    </p>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
@endsection

@section('scripts')
<script>
    const tokenPerQuestion = {{ $tokenPerQuestion }};
    
    function updateTokenCost() {
        const count = parseInt(document.getElementById('question_count_input').value) || 0;
        document.getElementById('token-cost-display').innerText = count * tokenPerQuestion;
    }

    function switchTab(mode) {
        if (mode === 'ai') {
            document.getElementById('tab-ai-content').style.display = 'block';
            document.getElementById('tab-manual-content').style.display = 'none';
            document.getElementById('tab-ai-btn').className = 'btn btn-primary';
            document.getElementById('tab-manual-btn').className = 'btn btn-secondary';
        } else {
            document.getElementById('tab-ai-content').style.display = 'none';
            document.getElementById('tab-manual-content').style.display = 'block';
            document.getElementById('tab-ai-btn').className = 'btn btn-secondary';
            document.getElementById('tab-manual-btn').className = 'btn btn-primary';
        }
    }

    function showLoadingState() {
        document.getElementById('ai-loading-overlay').style.display = 'flex';
        document.getElementById('ai-submit-btn').disabled = true;
        document.getElementById('ai-submit-btn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    }
</script>
@endsection
