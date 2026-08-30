<?php

namespace App\Services;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class NewsService extends BaseService
{
    /**
     * The foundation's operating timezone, used to interpret date-only admin
     * inputs as the start of that calendar day in local time before converting
     * to UTC for storage.
     */
    private string $tz;

    public function __construct()
    {
        $this->tz = config('foundation.local_timezone', 'Asia/Riyadh');
    }

    public function list(array $filters = [])
    {
        return News::query()
            ->select('id', 'title_ar', 'title_en', 'slug', 'status', 'published_at', 'external_link', 'created_by', 'created_at', 'updated_at')
            ->with('media')
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, fn($q, $s) => $q->where('title_ar', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15);
    }

    public function create(array $data): News
    {
        $image = $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media']);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title_ar']);
        $data['created_by'] = auth()->id();

        $this->normalizeDateFields($data);

        $news = News::create($data);

        $this->attachMedia($news, $image, $externalLink, $removeMedia, 'news_images');
        $this->attachSeo($news, $data);

        Cache::forget('home.latest_news');
        Cache::forget('dashboard.news_count');

        return $news;
    }

    public function update(News $news, array $data): News
    {
        $image = $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media']);

        $this->normalizeDateFields($data);

        $news->update($data);

        $this->attachMedia($news, $image, $externalLink, $removeMedia, 'news_images');
        $this->attachSeo($news, $data);

        Cache::forget('home.latest_news');

        return $news;
    }


    public function delete(News $news): bool
    {
        Cache::forget('home.latest_news');
        Cache::forget('dashboard.news_count');
        return $news->delete();
    }

    /**
     * Normalize admin-entered date-only published_at to UTC-stored timestamp.
     *
     * Only applies when published_at is explicitly present in the request data
     * (i.e. the admin manually filled the date picker). The auto-set now() that
     * fires in News::setIsActiveAttribute() when toggling status to 'published'
     * is a real-time event and must remain UTC — it is NOT passed through here.
     *
     * published_at → start of that calendar day in local tz → UTC
     *
     * @param  array<string, mixed>  $data  validated request data (by reference)
     */
    private function normalizeDateFields(array &$data): void
    {
        if (array_key_exists('published_at', $data) && !empty($data['published_at'])) {
            $data['published_at'] = Carbon::parse($data['published_at'], $this->tz)
                ->startOfDay()
                ->utc();
        }
    }
}