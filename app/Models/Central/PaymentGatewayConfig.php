<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayConfig extends Model
{
    protected $connection = 'mysql';
    protected $table = 'payment_gateway_configs';

    protected $fillable = [
        'gateway',
        'display_name',
        'credentials',
        'mode',
        'is_active',
        'webhook_url',
    ];

    protected $hidden = [
        'credentials',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'encrypted:array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get a specific credential value.
     */
    public function getCredential(string $key, $default = null)
    {
        return data_get($this->credentials, $key, $default);
    }

    /**
     * Check if in production mode.
     */
    public function isProduction(): bool
    {
        return $this->mode === 'production';
    }
}
