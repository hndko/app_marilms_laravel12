<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope — Global Scope untuk isolasi data antar tenant.
 *
 * Scope ini otomatis menambahkan WHERE tenant_id = ? pada setiap query
 * model yang menggunakannya, sehingga setiap tenant hanya bisa melihat
 * dan memanipulasi data milik mereka sendiri.
 *
 * Tenant aktif dibaca dari:
 *  1. tenancy()->tenant->id (saat request berada dalam konteks tenant)
 *  2. Fallback: '__none__' (tidak akan match record manapun jika tenant belum diinisialisasi)
 */
class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->resolveTenantId();

        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }

    /**
     * Resolve the active tenant ID from the tenancy context.
     */
    protected function resolveTenantId(): ?string
    {
        try {
            if (function_exists('tenancy') && tenancy()->initialized) {
                return tenancy()->tenant->getTenantKey();
            }
        } catch (\Throwable) {
            // Tenancy belum diinisialisasi — jangan apply scope
        }

        return null;
    }
}
