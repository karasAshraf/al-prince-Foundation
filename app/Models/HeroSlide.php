<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HeroSlide extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'image',
        'button_text_ar',
        'button_text_en',
        'button_url',
        'placement',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300);

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600);

        $this->addMediaConversion('hero')
            ->width(1920)
            ->height(1080)
            ->format('webp')
            ->performOnCollections('hero_slide_images');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeForPlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($model->order === null || $model->order === '') {
                $model->order = static::where('placement', $model->placement)->max('order') + 1;
            }
        });

        static::created(function ($model) {
            \App\Helpers\OrderHelper::moveTo($model, $model->order, ['placement' => $model->placement]);
        });

        static::updated(function ($model) {
            if ($model->wasChanged('order') || $model->wasChanged('placement')) {
                if ($model->wasChanged('placement')) {
                    \App\Helpers\OrderHelper::normalize(static::class, ['placement' => $model->getOriginal('placement')]);
                }
                \App\Helpers\OrderHelper::moveTo($model, $model->order, ['placement' => $model->placement]);
            }
        });

        static::deleted(function ($model) {
            \App\Helpers\OrderHelper::normalize(static::class, ['placement' => $model->placement]);
        });
    }
}
