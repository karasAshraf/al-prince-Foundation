<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GovernanceDocument extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'governance_documents';

    protected $fillable = [
        'title_ar',
        'title_en',
        'category',
        'fiscal_year',
        'file_path',
        'file_size',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'fiscal_year' => 'integer',
            'file_size' => 'integer',
            'order' => 'integer',
        ];
    }

    public const CATEGORY_POLICIES = 'policies';
    public const CATEGORY_FINANCIAL = 'financial_reports';
    public const CATEGORY_ACHIEVEMENT = 'achievement_reports';

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeYear(Builder $query, int $year): Builder
    {
        return $query->where('fiscal_year', $year);
    }

    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public static function availableYears(): Collection
    {
        return static::active()->distinct()->orderByDesc('fiscal_year')->pluck('fiscal_year');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($model->order === null || $model->order === '') {
                $model->order = static::where('category', $model->category)->max('order') + 1;
            }
        });

        static::created(function ($model) {
            \App\Helpers\OrderHelper::moveTo($model, $model->order, ['category' => $model->category]);
        });

        static::updated(function ($model) {
            if ($model->wasChanged('order') || $model->wasChanged('category')) {
                if ($model->wasChanged('category')) {
                    \App\Helpers\OrderHelper::normalize(static::class, ['category' => $model->getOriginal('category')]);
                }
                \App\Helpers\OrderHelper::moveTo($model, $model->order, ['category' => $model->category]);
            }
        });

        static::deleted(function ($model) {
            \App\Helpers\OrderHelper::normalize(static::class, ['category' => $model->category]);
        });
    }
}
