<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Support\Facades\Cache;

class PartnerService extends BaseService
{
    public function list(array $filters = [])
    {
        return Partner::query()
            ->with('media')
            ->when(isset($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): Partner
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $partner = Partner::create($data);

        $this->attachMedia($partner, $image, $externalLink, $removeMedia, 'partner_logos');

        Cache::forget('home.active_partners');

        return $partner;
    }

    public function update(Partner $partner, array $data): Partner
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $partner->update($data);

        $this->attachMedia($partner, $image, $externalLink, $removeMedia, 'partner_logos');

        Cache::forget('home.active_partners');

        return $partner;
    }

    public function delete(Partner $partner): bool
    {
        Cache::forget('home.active_partners');
        return $partner->delete();
    }
}
