<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ServiceService extends BaseService
{
    public function list(array $filters = [])
    {
        return Service::query()
            ->with('media')
            ->when($filters['is_active'] ?? null, fn($q, $a) => $q->where('is_active', $a))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): Service
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $data['slug'] = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title_ar']);

        $service = Service::create($data);

        $this->attachMedia($service, $image, $externalLink, $removeMedia, 'service_images');
        $this->attachSeo($service, $data);

        Cache::forget('home.active_services');

        return $service;
    }

    public function update(Service $service, array $data): Service
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        $service->update($data);

        $this->attachMedia($service, $image, $externalLink, $removeMedia, 'service_images');
        $this->attachSeo($service, $data);

        Cache::forget('home.active_services');

        return $service;
    }


    public function delete(Service $service): bool
    {
        Cache::forget('home.active_services');
        return $service->delete();
    }
}