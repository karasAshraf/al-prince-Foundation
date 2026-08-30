<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomePageSection extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300);

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600);

        // Hero slider LCP image: full-viewport WebP for maximum performance
        $this->addMediaConversion('hero')
            ->width(1920)
            ->height(1080)
            ->format('webp')
            ->performOnCollections('home_section_images');
    }

    protected $fillable = [
        'type',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'image',
        'extra_link',
        'label_ar',
        'label_en',
        'data',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
            'data'      => 'array',
        ];
    }

    
    public const TYPE_SLIDER = 'slider';
    public const TYPE_HOME_SECTION = 'home_section';
    public const TYPE_COUNTER = 'counter';
    public const TYPE_COUNTERS = 'counters';
    public const TYPE_LATEST_NEWS = 'latest_news';
    public const TYPE_SERVICE_SECTION = 'service_section';
    public const TYPE_ABOUT_PREVIEW = 'about_preview';
    public const TYPE_PROJECTS_PREVIEW = 'projects_preview';
    public const TYPE_HERO_SLIDER = 'hero_slider';
    public const TYPE_CTA = 'cta';

    public function getLabelAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->label_ar : ($this->label_en ?? $this->label_ar);
    }

    public function getCounterNumberAttribute(): ?string
    {
        return $this->data['counter_number'] ?? $this->data['number'] ?? null;
    }

    public function getCounterIconAttribute(): ?string
    {
        return $this->data['counter_icon'] ?? $this->data['icon'] ?? $this->image;
    }

    public function getPersonNameArAttribute(): ?string
    {
        return $this->data['person_name_ar'] ?? null;
    }

    public function getPersonNameEnAttribute(): ?string
    {
        return $this->data['person_name_en'] ?? null;
    }

    public function getPersonNameAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->person_name_ar : ($this->person_name_en ?? $this->person_name_ar);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}

