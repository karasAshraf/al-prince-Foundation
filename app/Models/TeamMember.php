<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TeamMember extends Model implements HasMedia
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
        'position_ar',
        'position_en',
        'bio_ar',
        'bio_en',
        'type',
        'image',
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

    public const TYPE_EXECUTIVE = 'executive';
    public const TYPE_BOARD = 'board';

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeBoard(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_BOARD)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id');
    }

    public function scopeExecutive(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_EXECUTIVE)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id');
    }

    // ─── Localized Accessors ──────────────────────────────────────────────────

    /**
     * Return the member's name in the current application locale,
     * falling back to Arabic if the English value is absent.
     */
    public function localizedName(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->name_ar : ($this->name_en ?: $this->name_ar)) ?? '';
    }

    /**
     * Return the member's position/title in the current application locale.
     */
    public function localizedPosition(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->position_ar : ($this->position_en ?: $this->position_ar)) ?? '';
    }

    /**
     * Return the member's biography in the current application locale.
     */
    public function localizedBio(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->bio_ar : ($this->bio_en ?: $this->bio_ar)) ?? '';
    }
}
