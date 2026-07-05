<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Participant user model within a tenant.
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $guard_name = 'participant';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all quiz attempts for this participant.
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get all quiz participations.
     */
    public function quizParticipants(): HasMany
    {
        return $this->hasMany(QuizParticipant::class);
    }

    /**
     * Check if participant is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
