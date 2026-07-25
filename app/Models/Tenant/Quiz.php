<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quiz extends Model
{
    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'category',
        'difficulty',
        'question_count',
        'option_count',
        'duration_minutes',
        'passing_score',
        'retry_limit',
        'is_public',
        'status',
        'deadline_at',
        'prompt_topic',
    ];

    protected function casts(): array
    {
        return [
            'question_count' => 'integer',
            'option_count' => 'integer',
            'duration_minutes' => 'integer',
            'passing_score' => 'integer',
            'retry_limit' => 'integer',
            'is_public' => 'boolean',
            'deadline_at' => 'datetime',
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

    /**
     * Get all questions for this quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get all attempts for this quiz.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get assigned participants (for non-public quizzes).
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quiz_participants')
            ->withTimestamps();
    }

    /**
     * Check if quiz is active and accessible.
     */
    public function isAccessible(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->deadline_at && $this->deadline_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if a user can take this quiz.
     */
    public function canUserTake(User $user): bool
    {
        if (!$this->isAccessible()) {
            return false;
        }

        if ($this->is_public) {
            return true;
        }

        return $this->participants()->where('tenant_users.id', $user->id)->exists();
    }

    /**
     * Get the number of remaining attempts for a user.
     * Returns null if unlimited.
     */
    public function remainingAttempts(User $user): ?int
    {
        if ($this->retry_limit === 0) {
            return null; // unlimited
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $user->id)
            ->count();

        return max(0, $this->retry_limit - $attemptCount);
    }

    /**
     * Check if user has already passed this quiz.
     */
    public function hasUserPassed(User $user): bool
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->where('score', '>=', $this->passing_score)
            ->whereIn('status', ['submitted', 'timeout', 'force_ended'])
            ->exists();
    }

    /**
     * Calculate total duration in seconds.
     */
    public function getTotalDurationSeconds(): int
    {
        if ($this->duration_minutes) {
            return $this->duration_minutes * 60;
        }

        // Use default_seconds_per_question from system settings
        $defaultSeconds = \App\Models\Central\SystemSetting::getValue('default_seconds_per_question', 60);
        return $this->questions()->count() * $defaultSeconds;
    }

    /**
     * Scope active quizzes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
