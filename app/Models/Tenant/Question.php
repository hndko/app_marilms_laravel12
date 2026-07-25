<?php

namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'tenant_id',
        'quiz_id',
        'question_text',
        'order',
        'difficulty',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
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

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    /**
     * Get the correct option for this question.
     */
    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
