<?php

namespace App\Services;

use App\Models\AboutSection;

class AboutSectionService extends BaseService
{
    public function list(array $filters = [])
    {
        return AboutSection::query()
            ->select('id', 'title_ar', 'title_en', 'status', 'order', 'created_at', 'updated_at')
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): AboutSection
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? $data['video'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $section = AboutSection::create($data);

        $this->attachMedia($section, $image, $externalLink, $removeMedia, 'about_images');
        $this->attachSeo($section, $data);

        return $section;
    }

    public function update(AboutSection $section, array $data): AboutSection
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? $data['video'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $section->update($data);

        $this->attachMedia($section, $image, $externalLink, $removeMedia, 'about_images');
        $this->attachSeo($section, $data);

        return $section;
    }


    public function delete(AboutSection $section): bool
    {
        return $section->delete();
    }
}