<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class WhatsappGatewayConfig extends Model
{
    protected $connection = 'mysql';
    protected $table = 'whatsapp_gateway_configs';

    protected $fillable = [
        'provider',
        'api_key',
        'sender_number',
        'is_active',
        'is_default',
        'owner_id',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('owner_id');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
