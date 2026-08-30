<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, InteractsWithMedia;

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
        'program_id',
        'title_ar',
        'title_en',
        'slug',
        'description_ar',
        'description_en',
        'goal_ar',
        'goal_en',
        'start_date',
        'end_date',
        'project_status',
        'status',
        'image',
        'external_link',
        'is_active',
    ];

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'published';
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'published' : 'draft';
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public const PROJECT_STATUS_ONGOING   = 'ongoing';
    public const PROJECT_STATUS_COMPLETED = 'completed';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title_ar')
            ->saveSlugsTo('slug');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo_metable');
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('project_status', self::PROJECT_STATUS_ONGOING);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('project_status', self::PROJECT_STATUS_COMPLETED);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $this->scopePublished($query);
    }
}
