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

class Activity extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        // للصورة الرئيسية (featured_image collection)
        $this->addMediaConversion('thumb')
            ->width(150)->height(150)->format('webp')
            ->performOnCollections('featured_image');

        $this->addMediaConversion('card')
            ->width(400)->height(300)->format('webp')
            ->performOnCollections('featured_image');

        $this->addMediaConversion('detail')
            ->width(800)->height(600)->format('webp')
            ->performOnCollections('featured_image');

        // لصور المعرض (gallery collection)
        $this->addMediaConversion('gallery_thumb')
            ->width(300)->height(300)->format('webp')
            ->performOnCollections('gallery');
    }

    protected $fillable = [
        'title_ar', 'title_en', 'slug',
        'description_ar', 'description_en',
        'order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title_ar')
            ->saveSlugsTo('slug');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo_metable');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}