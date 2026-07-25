<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

/**
 * Tenant model — single-database tenancy.
 * Tidak lagi implement TenantWithDatabase; tidak ada pembuatan database terpisah.
 * Setiap tenant hanya merupakan record di tabel 'tenants' dan
 * diidentifikasi via kolom tenant_id pada tabel-tabel tenant.
 */
class Tenant extends BaseTenant
{
    use HasDomains;

    /**
     * Custom columns on the tenants table.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'slug',
            'name',
            'owner_id',
            'is_active',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'slug',
        'name',
        'owner_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the owner that owns this tenant.
     */
    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class, 'id', 'owner_id');
    }
}
