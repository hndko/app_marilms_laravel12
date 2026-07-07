@extends('layouts.participant')

@section('title', 'Mengerjakan: ' . $quiz->title)

@section('styles')
<style>
    .option-card {
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--bg-input);
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .option-card:hover {
        border-color: var(--primary-light);
        background: rgba(99,102,241,0.08);
    }
    .option-card.selected {
        border-color: var(--primary);
        background: rgba(99,102,241,0.15);
        box-shadow: 0 0 15px rgba(99,102,241,0.2);
    }
    .nav-btn {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg-input);
        color: var(--text-white);
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .nav-btn:hover {
        border-color: var(--primary-light);
    }
    .nav-btn.answered {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }
    .timer-badge {
        background: rgba(239,68,68,0.15);
        border: 1px solid var(--danger);
        color: white;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 0 15px rgba(239,68,68,0.3);
    }
    .timer-badge.warning {
        animation: pulse 1s infinite;
        background: var(--danger);
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
</style>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Sticky Exam Header -->
    <div class="card" style="position: sticky; top: 16px; z-index: 500; background: rgba(22,25,35,0.95); backdrop-filter: blur(12px); border: 1px solid var(--primary-light); padding: 14px 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="badge badge-primary" style="font-size: 13px; padding: 6px 14px;">Ujian Berlangsung</span>
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: white; margin: 0;">{{ $quiz->title }}</h3>
                    <span style="font-size: 12px; color: var(--text-muted);"><i class="fas fa-list-ol"></i> Total {{ $questions->count() }} Soal</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <!-- Auto-Save Status Indicator -->
                <div id="save-status" style="font-size: 12px; font-weight: 600; color: var(--success); display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check-circle"></i> Jawaban Tersimpan
                </div>

                <!-- Live Timer Badge -->
                <div id="timer-badge" class="timer-badge">
                    <i class="fas fa-stopwatch"></i>
                    <span id="timer-display">--:--</span>
                </div>

                <!-- Submit Button -->
                <button type="button" onclick="confirmSubmit()" class="btn btn-primary" style="padding: 10px 24px; background: linear-gradient(135deg, var(--success), #059669); font-weight: 800;">
                    <i class="fas fa-paper-plane"></i> Kumpulkan Ujian
                </button>
            </div>
        </div>
    </div>

    <!-- Anti-Cheat Warning Banner (Hidden by default) -->
    <div id="cheat-warning" style="display: none; background: rgba(239,68,68,0.2); border: 2px solid var(--danger); border-radius: 12px; padding: 16px 20px; color: white; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: var(--danger);"></i>
            <div>
                <strong style="font-size: 15px; display: block;">PERINGATAN ANTI-CHEAT TERDETEKSI!</strong>
                <span style="font-size: 13px; color: var(--text-secondary);">Anda terdeteksi meninggalkan halaman atau membuka tab lain. Pelanggaran berulang akan menyebabkan ujian dikumpulkan paksa.</span>
            </div>
        </div>
        <button type="button" onclick="this.parentElement.style.display='none'" class="btn btn-sm btn-secondary">Saya Mengerti</button>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: flex-start;">
        
        <!-- Left Column: Question Cards -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            @foreach($questions as $index => $question)
                @php
                    $qNum = $index + 1;
                    $answeredOptionId = $existingAnswers->get($question->id)?->selected_option_id;
                @endphp

                <div class="card" id="question-card-{{ $qNum }}" style="padding: 24px; scroll-margin-top: 100px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="width: 34px; height: 34px; border-radius: 10px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px;">
                                {{ $qNum }}
                            </span>
                            <span style="font-size: 14px; font-weight: 700; color: white;">Pertanyaan #{{ $qNum }}</span>
                        </div>
                        <span class="badge badge-info">Bobot: {{ $question->points }} Poin</span>
                    </div>

                    <div style="font-size: 16px; font-weight: 600; color: white; line-height: 1.6; margin-bottom: 20px;">
                        {{ $question->question_text }}
                    </div>

                    <!-- Options List -->
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($question->options as $optIdx => $option)
                            @php
                                $letter = chr(65 + $optIdx); // A, B, C, D...
                                $isSelected = $answeredOptionId == $option->id;
                            @endphp

                            <div class="option-card {{ $isSelected ? 'selected' : '' }}" id="opt-card-{{ $question->id }}-{{ $option->id }}" onclick="selectOption({{ $question->id }}, {{ $option->id }}, {{ $qNum }})">
                                <div style="width: 28px; height: 28px; border-radius: 8px; border: 2px solid {{ $isSelected ? 'var(--primary)' : 'var(--border)' }}; background: {{ $isSelected ? 'var(--primary)' : 'transparent' }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; flex-shrink: 0;">
                                    {{ $letter }}
                                </div>
                                <span style="font-size: 14px; color: var(--text-white); font-weight: {{ $isSelected ? '700' : '500' }}; flex: 1;">
                                    {{ $option->option_text }}
                                </span>
                                <i class="fas fa-check-circle" style="color: var(--primary); font-size: 18px; display: {{ $isSelected ? 'block' : 'none' }};" id="check-icon-{{ $question->id }}-{{ $option->id }}"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Right Column: Question Navigator Sidebar -->
        <div class="card" style="position: sticky; top: 108px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                <i class="fas fa-th-large" style="color: var(--accent);"></i>
                <h4 style="font-size: 15px; font-weight: 800; color: white; margin: 0;">Navigasi Soal</h4>
            </div>

            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px;" id="navigator-grid">
                @foreach($questions as $index => $question)
                    @php
                        $qNum = $index + 1;
                        $isAnswered = $existingAnswers->has($question->id);
                    @endphp
                    <div class="nav-btn {{ $isAnswered ? 'answered' : '' }}" id="nav-btn-{{ $qNum }}" onclick="scrollToQuestion({{ $qNum }})" title="Soal #{{ $qNum }}">
                        {{ $qNum }}
                    </div>
                @endforeach
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 14px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: var(--success); display: inline-block;"></span>
                    <span>Sudah Dijawab (<span id="count-answered">{{ $existingAnswers->count() }}</span>)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: var(--bg-input); border: 1px solid var(--border); display: inline-block;"></span>
                    <span>Belum Dijawab (<span id="count-unanswered">{{ $questions->count() - $existingAnswers->count() }}</span>)</span>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="button" onclick="confirmSubmit()" class="btn btn-primary" style="width: 100%; justify-content: center; background: linear-gradient(135deg, var(--success), #059669); font-weight: 800; padding: 12px;">
                    <i class="fas fa-check-double"></i> Kumpulkan Sekarang
                </button>
            </div>
        </div>

    </div>

</div>

<!-- Submit Confirmation Modal -->
<div id="submit-modal" style="display: none; position: fixed; inset: 0; background: rgba(15,17,23,0.85); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="width: 100%; max-width: 480px; border-color: var(--success); box-shadow: 0 20px 50px rgba(0,0,0,0.8); text-align: center; padding: 32px;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(16,185,129,0.15); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
            <i class="fas fa-question"></i>
        </div>
        <h3 style="font-size: 20px; font-weight: 800; color: white;">Konfirmasi Pengumpulan Ujian</h3>
        <p style="font-size: 14px; color: var(--text-secondary); margin: 12px 0 24px; line-height: 1.6;">
            Apakah Anda yakin ingin mengakhiri dan mengumpulkan ujian ini sekarang? Pastikan semua soal telah dijawab dengan teliti.
        </p>
        
        <form method="POST" action="{{ route('tenant.participant.quiz.attempt.submit', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $attempt->id]) }}" id="submit-form">
            @csrf
            <input type="hidden" name="reason" value="manual">
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" onclick="document.getElementById('submit-modal').style.display='none'" class="btn btn-secondary" style="flex: 1; justify-content: center; padding: 12px;">Batal</button>
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; padding: 12px; background: var(--success); font-weight: 800;">Ya, Kumpulkan!</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let remainingSeconds = {{ (int) $remainingSeconds }};
    const attemptId = {{ $attempt->id }};
    const tenantSlug = "{{ $tenant ?? request()->segment(1) }}";
    const totalQuestions = {{ $questions->count() }};
    const saveAnswerUrl = "{{ route('tenant.participant.quiz.attempt.answer', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $attempt->id]) }}";
    const remainingUrl = "{{ route('tenant.participant.quiz.attempt.remaining', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $attempt->id]) }}";
    const forceSubmitUrl = "{{ route('tenant.quiz.attempt.force-submit', ['tenant' => $tenant ?? request()->segment(1), 'attempt' => $attempt->id]) }}";
    
    let timerInterval;
    let syncInterval;
    let tabSwitchCount = 0;

    function initTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            remainingSeconds--;
            updateTimerDisplay();
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                autoSubmitExpired();
            }
        }, 1000);

        // Sync with server every 30 seconds
        syncInterval = setInterval(syncServerTimer, 30000);
    }

    function updateTimerDisplay() {
        const display = document.getElementById('timer-display');
        const badge = document.getElementById('timer-badge');
        if (!display) return;

        if (remainingSeconds <= 0) {
            display.innerText = "00:00";
            badge.classList.add('warning');
            return;
        }

        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        display.innerText = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

        if (remainingSeconds < 60) {
            badge.classList.add('warning');
        } else {
            badge.classList.remove('warning');
        }
    }

    async function syncServerTimer() {
        try {
            const res = await fetch(remainingUrl, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.expired) {
                autoSubmitExpired();
            } else if (typeof data.remaining_seconds === 'number') {
                remainingSeconds = data.remaining_seconds;
                updateTimerDisplay();
            }
        } catch (e) {
            console.error('Timer sync failed:', e);
        }
    }

    function autoSubmitExpired() {
        alert("Waktu ujian Anda telah habis! Sistem akan mengumpulkan jawaban Anda secara otomatis.");
        const form = document.getElementById('submit-form');
        if (form) {
            form.querySelector('input[name="reason"]').value = 'time_up';
            form.submit();
        }
    }

    function scrollToQuestion(qNum) {
        const card = document.getElementById(`question-card-${qNum}`);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth' });
        }
    }

    async function selectOption(questionId, optionId, qNum) {
        // UI feedback immediately
        const cardContainer = document.getElementById(`question-card-${qNum}`);
        const allOpts = cardContainer.querySelectorAll('.option-card');
        allOpts.forEach(opt => {
            opt.classList.remove('selected');
            opt.style.borderColor = 'var(--border)';
            opt.style.background = 'var(--bg-input)';
            const icon = opt.querySelector('i');
            if (icon) icon.style.display = 'none';
        });

        const selectedOpt = document.getElementById(`opt-card-${questionId}-${optionId}`);
        if (selectedOpt) {
            selectedOpt.classList.add('selected');
            selectedOpt.style.borderColor = 'var(--primary)';
            selectedOpt.style.background = 'rgba(99,102,241,0.15)';
            const icon = document.getElementById(`check-icon-${questionId}-${optionId}`);
            if (icon) icon.style.display = 'block';
        }

        // Update navigator badge
        const navBtn = document.getElementById(`nav-btn-${qNum}`);
        if (navBtn) navBtn.classList.add('answered');
        updateAnsweredCounts();

        // Show saving status
        const saveStatus = document.getElementById('save-status');
        if (saveStatus) {
            saveStatus.innerHTML = '<i class="fas fa-spinner fa-spin" style="color: var(--warning);"></i> Menyimpan...';
        }

        // AJAX Auto-Save
        try {
            const res = await fetch(saveAnswerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    selected_option_id: optionId
                })
            });
            const data = await res.json();
            if (data.expired) {
                autoSubmitExpired();
            } else if (saveStatus) {
                saveStatus.innerHTML = '<i class="fas fa-check-circle" style="color: var(--success);"></i> Jawaban Tersimpan';
            }
        } catch (e) {
            console.error('Auto-save error:', e);
            if (saveStatus) {
                saveStatus.innerHTML = '<i class="fas fa-exclamation-circle" style="color: var(--danger);"></i> Gagal Menyimpan';
            }
        }
    }

    function updateAnsweredCounts() {
        const answeredCount = document.querySelectorAll('.nav-btn.answered').length;
        const unansweredCount = totalQuestions - answeredCount;
        const countAnsElem = document.getElementById('count-answered');
        const countUnansElem = document.getElementById('count-unanswered');
        if (countAnsElem) countAnsElem.innerText = answeredCount;
        if (countUnansElem) countUnansElem.innerText = unansweredCount;
    }

    function confirmSubmit() {
        document.getElementById('submit-modal').style.display = 'flex';
    }

    // Anti-Cheat: Tab Switch Detection
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            tabSwitchCount++;
            console.warn(`Anti-Cheat: Tab switch detected (#${tabSwitchCount})`);
            
            // Show warning banner when they return
        } else if (tabSwitchCount > 0) {
            const warning = document.getElementById('cheat-warning');
            if (warning) warning.style.display = 'flex';

            // If strict anti-cheat is enabled or exceeded 3 switches, force submit
            if (tabSwitchCount >= 3) {
                alert("Anda terdeteksi terlalu sering berpindah tab/halaman. Ujian Anda akan diakhiri dan dikumpulkan secara otomatis oleh sistem Anti-Cheat.");
                const form = document.getElementById('submit-form');
                if (form) {
                    form.querySelector('input[name="reason"]').value = 'tab_switch';
                    form.submit();
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        initTimer();
        updateAnsweredCounts();
    });
</script>
@endsection
