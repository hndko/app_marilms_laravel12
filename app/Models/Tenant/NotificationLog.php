<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    // Tabel diganti dari 'notifications_log' (lama di tenant DB) ke 'notification_logs' (central DB)
    protected $table = 'notification_logs';

    protected $fillable = [
        'tenant_id',
        'channel',
        'recipient',
        'subject',
        'body',
        'status',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (self $model) {
            if (empty($model->tenant_id)) {
                try {
                    if (function_exists('tenancy') && tenancy()->initialized) {
                        $model->tenant_id = tenancy()->tenant->getTenantKey();
                    }
                } catch (\Throwable) {}
            }
        });
    }
}
