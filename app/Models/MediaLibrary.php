<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class MediaLibrary extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, InteractsWithMedia;

    // لا يوجد registerMediaConversions() هنا عمدًا —
    // الملفات (PDF, DOCX, XLSX...) لا تحتاج تحويلات صور مثل WebP.

    protected $fillable = [
        'title_ar', 'title_en', 'slug',
        'description_ar', 'description_en',
        'category', 'file', 'external_link',
        'order', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    // ثوابت التصنيفات — تستخدم في الفورم والفلتر بدل نص حر
   public const CATEGORY_ANNUAL_REPORTS  = 'annual_reports';
public const CATEGORY_FINANCIAL       = 'financial_reports';
public const CATEGORY_ACHIEVEMENT     = 'achievement_reports';
public const CATEGORY_PUBLICATIONS    = 'publications';
public const CATEGORY_RESEARCH        = 'research';
public const CATEGORY_BROCHURES       = 'brochures';
public const CATEGORY_POLICIES        = 'policies';
public const CATEGORY_OTHER           = 'other';

public static function categories(): array
{
    return [
        self::CATEGORY_ANNUAL_REPORTS => 'التقارير السنوية',
        self::CATEGORY_FINANCIAL      => 'التقارير المالية',
        self::CATEGORY_ACHIEVEMENT    => 'تقارير الإنجاز',
        self::CATEGORY_PUBLICATIONS   => 'المنشورات',
        self::CATEGORY_RESEARCH       => 'الأبحاث والدراسات',
        self::CATEGORY_BROCHURES      => 'الكتيبات التعريفية',
        self::CATEGORY_POLICIES       => 'السياسات والوثائق',
        self::CATEGORY_OTHER          => 'أخرى',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }

    // هل المصدر ملف مرفوع محليًا ولا رابط خارجي؟ (مفيدة في الـ Blade)
    public function getSourceTypeAttribute(): string
    {
        return $this->external_link ? 'external' : 'file';
    }
}
