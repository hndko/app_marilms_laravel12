<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Participant user model — single-database tenancy.
 * Data disimpan di tabel 'tenant_users' bersama semua tenant,
 * difilter otomatis via TenantScope berdasarkan tenant_id aktif.
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tenant_users';

    protected $guard_name = 'participant';

    protected $fillable = [
        'tenant_id',
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
     * Boot: daftarkan TenantScope sebagai global scope.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        // Otomatis isi tenant_id saat create
        static::creating(function (self $model) {
            if (empty($model->tenant_id)) {
                try {
                    if (function_exists('tenancy') && tenancy()->initialized) {
                        $model->tenant_id = tenancy()->tenant->getTenantKey();
                    }
                } catch (\Throwable) {
                    // Biarkan kosong jika belum ada tenant context
                }
            }
        });
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
