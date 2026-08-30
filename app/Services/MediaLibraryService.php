<?php

namespace App\Services;

use App\Models\MediaLibrary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MediaLibraryService extends BaseService
{
    public function list(array $filters = [])
    {
        return MediaLibrary::query()
            ->with('media')
            ->when($filters['is_active'] ?? null, fn($q, $a) => $q->where('is_active', $a))
            ->when($filters['category'] ?? null, fn($q, $c) => $q->where('category', $c))
            ->when($filters['search'] ?? null, fn($q, $s) => $q->where('title_ar', 'like', "%{$s}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): MediaLibrary
    {
        $file = $data['file'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        unset($data['file']);

        if (is_array($externalLink)) {
            $filtered = array_values(array_filter($externalLink));
            $data['external_link'] = empty($filtered) ? null : (count($filtered) === 1 ? $filtered[0] : json_encode($filtered));
        } else {
            $data['external_link'] = !empty($externalLink) ? $externalLink : null;
        }
        
        $data['created_by'] = auth()->id();
        $data['slug'] = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title_ar']);

        $item = MediaLibrary::create($data);

        $this->attachDocument($item, $file, $data['external_link']);
        $this->attachSeo($item, $data);

        Cache::forget('media_library.active_items');

        return $item;
    }

    public function update(MediaLibrary $item, array $data): MediaLibrary
    {
        $file = $data['file'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        unset($data['file']);

        if (is_array($externalLink)) {
            $filtered = array_values(array_filter($externalLink));
            $data['external_link'] = empty($filtered) ? null : (count($filtered) === 1 ? $filtered[0] : json_encode($filtered));
        } else {
            $data['external_link'] = !empty($externalLink) ? $externalLink : null;
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        $item->update($data);

        $this->attachDocument($item, $file, $data['external_link']);
        $this->attachSeo($item, $data);

        Cache::forget('media_library.active_items');

        return $item;
    }

    public function delete(MediaLibrary $item): bool
    {
        Cache::forget('media_library.active_items');
        return $item->delete();
    }

    /**
     * ربط الملفات (رفع محلي أو رابط خارجي) — يدعم ملفات وروابط متعددة
     */
    protected function attachDocument(MediaLibrary $item, $file, $externalLink): void
    {
        // 1. حذف الملفات المطلوبة من Spatie Media
        $removeMediaIds = request('remove_media_ids', []);
        if (!empty($removeMediaIds)) {
            $item->media()->whereIn('id', $removeMediaIds)->delete();
        }

        // 2. رفع ملفات جديدة
        if ($file) {
            $filesArray = is_array($file) ? $file : [$file];
            foreach ($filesArray as $f) {
                if ($f instanceof \Illuminate\Http\UploadedFile) {
                    $item->addMedia($f)->toMediaCollection('media_library_files');
                }
            }
        }

        // 3. تحديث عمود file كمرجع للملف الأول فقط
        $firstMedia = $item->getFirstMedia('media_library_files');
        $item->update(['file' => $firstMedia ? $firstMedia->id . '/' . $firstMedia->file_name : null]);
    }

    public function categories(): array
    {
        return MediaLibrary::categories();
    }
}
