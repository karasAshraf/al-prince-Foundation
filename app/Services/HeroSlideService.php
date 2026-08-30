<?php

namespace App\Services;

use App\Models\HeroSlide;

class HeroSlideService extends BaseService
{
    public function list(array $filters = [])
    {
        return HeroSlide::query()
            ->when($filters['placement'] ?? null, fn($q, $p) => $q->where('placement', $p))
            ->orderBy('placement')
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): HeroSlide
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link'], $data['external_link']);

        $slide = HeroSlide::create($data);

        $this->attachMedia($slide, $image, $externalLink, $removeMedia, 'hero_slide_images');

        return $slide;
    }

    public function update(HeroSlide $slide, array $data): HeroSlide
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link'], $data['external_link']);

        $slide->update($data);

        $this->attachMedia($slide, $image, $externalLink, $removeMedia, 'hero_slide_images');

        return $slide;
    }

    public function delete(HeroSlide $slide): bool
    {
        return $slide->delete();
    }
}
