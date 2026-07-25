<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

class QuizParticipant extends Model
{
    protected $fillable = [
        'tenant_id',
        'quiz_id',
        'user_id',
    ];

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

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
