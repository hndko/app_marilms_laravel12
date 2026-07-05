<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenOrder extends Model
{
    use HasUuids;

    protected $connection = 'mysql';
    protected $table = 'token_orders';

    protected $fillable = [
        'owner_id',
        'package_id',
        'token_amount',
        'amount_idr',
        'gateway',
        'gateway_order_id',
        'status',
        'paid_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'token_amount' => 'integer',
            'amount_idr' => 'integer',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TokenPackage::class, 'package_id');
    }

    /**
     * Check if order is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if order is successful.
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_idr, 0, ',', '.');
    }
}
