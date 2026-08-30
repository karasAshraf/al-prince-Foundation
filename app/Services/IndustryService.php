<?php

namespace App\Services;

use App\Models\Industry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class IndustryService extends BaseService
{
    public function list(array $filters = [])
    {
        return Industry::query()
            ->with('media')
            ->when($filters['is_active'] ?? null, fn($q, $a) => $q->where('is_active', $a))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): Industry
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);

        $data['slug'] = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title_ar']);

        $industry = Industry::create($data);

        $this->attachMedia($industry, $image, $externalLink, $removeMedia, 'industry_images');
        $this->attachSeo($industry, $data);

        Cache::forget('home.active_industries');

        return $industry;
    }

    public function update(Industry $industry, array $data): Industry
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        $industry->update($data);

        $this->attachMedia($industry, $image, $externalLink, $removeMedia, 'industry_images');
        $this->attachSeo($industry, $data);

        Cache::forget('home.active_industries');

        return $industry;
    }

    public function delete(Industry $industry): bool
    {
        Cache::forget('home.active_industries');
        return $industry->delete();
    }
}