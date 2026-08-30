<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrganizationalStructure extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'image_ar',
        'image_en',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('organizational_structure_ar')
            ->singleFile();
            
        $this->addMediaCollection('organizational_structure_en')
            ->singleFile();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}