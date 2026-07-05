<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Owner extends Authenticatable
{
    use HasUuids, Notifiable, HasRoles;

    protected $connection = 'mysql';
    protected $table = 'owners';
    protected $guard_name = 'owner';

    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_name',
        'slug',
        'phone',
        'status',
        'type',
        'email_verified_at',
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
     * Get the tenant associated with this owner.
     */
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class, 'owner_id');
    }

    /**
     * Get the token balance for this owner.
     */
    public function tokenBalance(): HasOne
    {
        return $this->hasOne(OwnerTokenBalance::class);
    }

    /**
     * Get all token transactions for this owner.
     */
    public function tokenTransactions(): HasMany
    {
        return $this->hasMany(TokenTransaction::class);
    }

    /**
     * Get all token orders for this owner.
     */
    public function tokenOrders(): HasMany
    {
        return $this->hasMany(TokenOrder::class);
    }

    /**
     * Check if owner has unlimited tokens.
     */
    public function isUnlimited(): bool
    {
        return $this->tokenBalance?->is_unlimited ?? false;
    }

    /**
     * Check if owner is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if owner is regular type.
     */
    public function isRegular(): bool
    {
        return $this->type === 'regular';
    }

    /**
     * Get the current token balance.
     */
    public function getTokenBalanceAmount(): int
    {
        return $this->tokenBalance?->balance ?? 0;
    }
}
