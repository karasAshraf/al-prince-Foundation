<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class AboutSection extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        // Thumbnail for dashboard/lists
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->format('webp');

        // Card/preview: side column in about page (≈640px wide, 4:3 aspect)
        $this->addMediaConversion('card')
            ->width(640)
            ->height(480)
            ->format('webp');

        // Detail: full-width section image on about/show pages (max-w-4xl, 4:3)
        $this->addMediaConversion('detail')
            ->width(960)
            ->height(720)
            ->format('webp');
    }

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'description_ar',
        'description_en',
        'status',
        'image',
        'video',
        'external_link',
        'is_active',
        'order',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($model->order === null || $model->order === '') {
                $model->order = static::max('order') + 1;
            }
        });

        static::created(function ($model) {
            \App\Helpers\OrderHelper::moveTo($model, $model->order);
        });

        static::updated(function ($model) {
            if ($model->wasChanged('order')) {
                \App\Helpers\OrderHelper::moveTo($model, $model->order);
            }
        });

        static::deleted(function ($model) {
            \App\Helpers\OrderHelper::normalize(static::class);
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title_ar')
            ->saveSlugsTo('slug');
    }

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? self::STATUS_PUBLISHED : self::STATUS_DRAFT;
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo_metable');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $this->scopePublished($query);
    }
}