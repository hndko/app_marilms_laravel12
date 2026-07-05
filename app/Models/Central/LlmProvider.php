<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class LlmProvider extends Model
{
    protected $connection = 'mysql';
    protected $table = 'llm_providers';

    protected $fillable = [
        'name',
        'provider_type',
        'base_url',
        'api_key',
        'model',
        'max_tokens',
        'temperature',
        'priority',
        'status',
        'owner_id',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'max_tokens' => 'integer',
            'temperature' => 'float',
            'priority' => 'integer',
        ];
    }

    /**
     * Scope for active providers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for global (non-owner-specific) providers.
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('owner_id');
    }

    /**
     * Scope ordered by priority (1 = highest).
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority');
    }

    /**
     * Scope for fallback providers.
     */
    public function scopeFallback($query)
    {
        return $query->where('status', 'fallback');
    }

    /**
     * Get all available providers (active + fallback) ordered by priority.
     */
    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['active', 'fallback'])->orderBy('priority');
    }
}
