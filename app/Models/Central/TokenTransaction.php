<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenTransaction extends Model
{
    use HasUuids;

    protected $connection = 'mysql';
    protected $table = 'token_transactions';
    public $timestamps = false;

    protected $fillable = [
        'owner_id',
        'type',
        'amount',
        'source',
        'reference_id',
        'note',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Scope to filter by type (debit/credit).
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by source.
     */
    public function scopeOfSource($query, string $source)
    {
        return $query->where('source', $source);
    }
}
