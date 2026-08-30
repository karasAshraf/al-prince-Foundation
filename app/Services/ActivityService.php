<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ActivityService extends BaseService
{
    protected string $modelClass = Activity::class;
    protected string $cacheKey = 'home.active_activities';

    public function list(array $filters = [])
    {
        return $this->modelClass::query()
            ->with('media')
            ->when($filters['is_active'] ?? null, fn($q, $a) => $q->where('is_active', $a))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): Model
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $gallery = $data['gallery'] ?? [];
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['gallery'], $data['remove_media'], $data['media_external_link']);

        $data['slug'] = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title_ar']);

        $activity = $this->modelClass::create($data);

        $this->attachMedia($activity, $image, $externalLink, $removeMedia, 'featured_image');
        $this->attachGallery($activity, $gallery);
        $this->attachSeo($activity, $data);

        Cache::forget($this->cacheKey);

        return $activity;
    }

    public function update(Model $activity, array $data): Model
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $gallery = $data['gallery'] ?? [];
        $removeMedia = !empty($data['remove_media']);
        $removeGallery = $data['remove_gallery'] ?? [];
        unset($data['image'], $data['gallery'], $data['remove_media'], $data['remove_gallery'], $data['media_external_link']);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        $activity->update($data);

        $this->attachMedia($activity, $image, $externalLink, $removeMedia, 'featured_image');
        
        if (!empty($removeGallery)) {
            $activity->media()
                ->whereIn('id', $removeGallery)
                ->where('collection_name', 'gallery')
                ->get()
                ->each(fn($media) => $media->delete());
        }

        $this->attachGallery($activity, $gallery);
        $this->attachSeo($activity, $data);

        Cache::forget($this->cacheKey);

        return $activity;
    }

    public function delete(Model $activity): bool
    {
        Cache::forget($this->cacheKey);
        return $activity->delete();
    }

    /**
     * إضافة صور جديدة للمعرض (بدون حذف الصور القديمة تلقائيًا)
     */
    protected function attachGallery(Model $activity, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $activity->addMedia($file)->toMediaCollection('gallery');
            }
        }
    }
}