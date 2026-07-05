<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OwnerTokenBalance extends Model
{
    protected $connection = 'mysql';
    protected $table = 'owner_token_balances';

    protected $fillable = [
        'owner_id',
        'balance',
        'is_unlimited',
    ];

    protected function casts(): array
    {
        return [
            'is_unlimited' => 'boolean',
            'balance' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }
}
