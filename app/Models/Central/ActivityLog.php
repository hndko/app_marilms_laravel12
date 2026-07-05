<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mysql';
    protected $table = 'activity_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Log an activity.
     */
    public static function log(string $action, string $description, ?string $userType = null, ?string $userId = null, array $properties = []): static
    {
        return static::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
