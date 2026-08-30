<?php

namespace App\Services;

use App\Models\Solution;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SolutionService extends BaseService
{
    public function list(array $filters = [])
    {
        return Solution::query()
            ->with('media')
            ->when($filters['is_active'] ?? null, fn($q, $a) => $q->where('is_active', $a))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): Solution
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $data['slug'] = !empty($data['slug']) ? $data['slug'] : Str::slug($data['title_ar']);

        $solution = Solution::create($data);

        $this->attachMedia($solution, $image, $externalLink, $removeMedia, 'solution_images');
        $this->attachSeo($solution, $data);

        Cache::forget('home.active_solutions');

        return $solution;
    }

    public function update(Solution $solution, array $data): Solution
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_ar']);
        }

        $solution->update($data);

        $this->attachMedia($solution, $image, $externalLink, $removeMedia, 'solution_images');
        $this->attachSeo($solution, $data);

        Cache::forget('home.active_solutions');

        return $solution;
    }

    public function delete(Solution $solution): bool
    {
        Cache::forget('home.active_solutions');
        return $solution->delete();
    }
}
