<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    protected $fillable = [
        'tenant_id',
        'question_id',
        'option_text',
        'is_correct',
        'explanation',
        'order',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'order' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::saving(function (self $model) {
            if (isset($model->order) && !isset($model->sort_order)) {
                $model->sort_order = $model->order;
            } elseif (isset($model->sort_order) && !isset($model->order)) {
                $model->order = $model->sort_order;
            }
        });

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

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
