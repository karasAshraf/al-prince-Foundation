<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'seo_metable_id',
        'seo_metable_type',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords',
        'canonical_url',
        'og_image',
    ];

    public function seo_metable(): MorphTo
    {
        return $this->morphTo();
    }
}