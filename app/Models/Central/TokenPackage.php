<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class TokenPackage extends Model
{
    protected $connection = 'mysql';
    protected $table = 'token_packages';

    protected $fillable = [
        'name',
        'token_amount',
        'price_idr',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'token_amount' => 'integer',
            'price_idr' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope for active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered packages.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price_idr');
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_idr, 0, ',', '.');
    }
}
