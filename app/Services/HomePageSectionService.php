<?php

namespace App\Services;

use App\Models\HomePageSection;
use Illuminate\Support\Facades\Cache;

class HomePageSectionService extends BaseService
{
    public function list()
    {
        return HomePageSection::select('id', 'type', 'title_ar', 'title_en', 'label_ar', 'label_en', 'order', 'is_active', 'created_at', 'updated_at')
            ->with('media')
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): HomePageSection
    {
        $image = $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? $data['extra_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media']);

        // Merge counter and person fields into data JSON array
        $dataData = $data['data'] ?? [];
        if (isset($data['counter_number'])) {
            $dataData['counter_number'] = $data['counter_number'];
            unset($data['counter_number']);
        }
        if (isset($data['counter_icon'])) {
            $dataData['counter_icon'] = $data['counter_icon'];
            unset($data['counter_icon']);
        }
        if (isset($data['person_name_ar'])) {
            $dataData['person_name_ar'] = $data['person_name_ar'];
            unset($data['person_name_ar']);
        }
        if (isset($data['person_name_en'])) {
            $dataData['person_name_en'] = $data['person_name_en'];
            unset($data['person_name_en']);
        }
        $data['data'] = $dataData;

        // Auto-assign order if missing or null
        if (!isset($data['order']) || $data['order'] === null || $data['order'] === '') {
            $data['order'] = (HomePageSection::max('order') ?? 0) + 1;
        }

        $section = HomePageSection::create($data);

        $this->attachMedia(
            $section,
            $image,
            $externalLink,
            $removeMedia,
            'home_section_images',
            'image',
            'extra_link'
        );

        Cache::forget('home.active_sections');

        return $section;
    }

    public function update(HomePageSection $section, array $data): HomePageSection
    {
        $image = $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? $data['extra_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media']);

        // Merge counter and person fields into data JSON array
        $dataData = array_merge($section->data ?? [], $data['data'] ?? []);
        if (isset($data['counter_number'])) {
            $dataData['counter_number'] = $data['counter_number'];
            unset($data['counter_number']);
        }
        if (isset($data['counter_icon'])) {
            $dataData['counter_icon'] = $data['counter_icon'];
            unset($data['counter_icon']);
        }
        if (isset($data['person_name_ar'])) {
            $dataData['person_name_ar'] = $data['person_name_ar'];
            unset($data['person_name_ar']);
        }
        if (isset($data['person_name_en'])) {
            $dataData['person_name_en'] = $data['person_name_en'];
            unset($data['person_name_en']);
        }
        $data['data'] = $dataData;

        $section->update($data);

        $this->attachMedia(
            $section,
            $image,
            $externalLink,
            $removeMedia,
            'home_section_images',
            'image',
            'extra_link'
        );

        Cache::forget('home.active_sections');

        return $section;
    }


    public function delete(HomePageSection $section): bool
    {
        Cache::forget('home.active_sections');
        return $section->delete();
    }

    public function reorder(array $orderedIds): void
    {
        $cases = collect($orderedIds)
            ->map(fn($id, $index) => ['id' => $id, 'order' => $index])
            ->all();

        HomePageSection::upsert($cases, ['id'], ['order']);
        Cache::forget('home.active_sections');
    }

    // تُستخدم في صفحة الهوم بالفرونت
    public function activeSections()
    {
        return HomePageSection::active()->with('media')->get();
    }
}