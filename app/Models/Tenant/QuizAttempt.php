<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'quiz_id',
        'started_at',
        'submitted_at',
        'total_duration_seconds',
        'status',
        'end_reason',
        'is_flagged',
        'score',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'total_duration_seconds' => 'integer',
            'is_flagged' => 'boolean',
            'score' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (self $model) {
            if (empty($model->tenant_id)) {
                try {
                    if (function_exists('tenancy') && tenancy()->initialized) {
                        $model->tenant_id = tenancy()->tenant->getTenantKey();
                    }
                } catch (\Throwable) {}
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    /**
     * Calculate remaining seconds from server time.
     * This is the AUTHORITATIVE timer — client cannot manipulate.
     */
    public function getRemainingSeconds(): int
    {
        if ($this->status !== 'in_progress') {
            return 0;
        }

        $elapsed = abs($this->started_at->diffInSeconds(now()));
        $remaining = $this->total_duration_seconds - $elapsed;

        return max(0, (int) $remaining);
    }

    /**
     * Check if this attempt has expired.
     */
    public function isExpired(): bool
    {
        return $this->getRemainingSeconds() <= 0;
    }

    /**
     * Check if this attempt is still in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Submit this attempt and calculate the score.
     */
    public function submit(string $endReason = 'manual'): void
    {
        if (!$this->isInProgress()) {
            return; // Idempotent — already submitted
        }

        // Calculate score
        $totalQuestions = $this->quiz->questions()->count();
        $correctAnswers = $this->answers()->where('is_correct', true)->count();

        $score = $totalQuestions > 0
            ? round(($correctAnswers / $totalQuestions) * 100)
            : 0;

        $this->update([
            'status' => $endReason === 'time_up' ? 'timeout' : ($endReason === 'manual' ? 'submitted' : 'force_ended'),
            'end_reason' => $endReason,
            'submitted_at' => now(),
            'score' => $score,
            'is_flagged' => in_array($endReason, ['tab_switch', 'browser_close']),
        ]);
    }

    /**
     * Check if the participant passed.
     */
    public function hasPassed(): bool
    {
        return $this->score >= $this->quiz->passing_score;
    }

    /**
     * Get the end reason in human-readable format.
     */
    public function getEndReasonLabelAttribute(): string
    {
        return match ($this->end_reason) {
            'manual' => 'Selesai',
            'time_up' => 'Waktu Habis',
            'tab_switch' => 'Keluar dari Ujian (Tab Switch)',
            'browser_close' => 'Keluar dari Ujian (Browser Ditutup)',
            'admin_force' => 'Dihentikan Admin',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Scope for in-progress attempts.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for expired but not yet submitted attempts.
     */
    public function scopeExpiredNotSubmitted($query)
    {
        return $query->where('status', 'in_progress')
            ->whereRaw('TIMESTAMPADD(SECOND, total_duration_seconds, started_at) < NOW()');
    }
}
