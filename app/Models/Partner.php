<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Partner extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->format('webp');

        $this->addMediaConversion('card')
            ->width(400)
            ->height(300)
            ->format('webp');

        $this->addMediaConversion('detail')
            ->width(800)
            ->height(600)
            ->format('webp');
    }

    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'external_link',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function localizedName(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->name_ar : ($this->name_en ?: $this->name_ar)) ?? '';
    }
}
