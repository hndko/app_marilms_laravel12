<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class EmailGatewayConfig extends Model
{
    protected $connection = 'mysql';
    protected $table = 'email_gateway_configs';

    protected $fillable = [
        'driver',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
            'is_active' => 'boolean',
            'port' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
