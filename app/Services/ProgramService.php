<?php

namespace App\Services;

use App\Models\Program;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProgramService extends BaseService
{
    public function list(array $filters = [])
    {
        return Program::query()
            ->select('id', 'title_ar', 'title_en', 'slug', 'order', 'status', 'external_link', 'created_at', 'updated_at')
            ->withCount('projects')
            ->with('media')
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): Program
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $data['slug'] = $data['slug'] ?? Str::slug($data['title_ar']);

        $program = Program::create($data);

        $this->attachMedia($program, $image, $externalLink, $removeMedia, 'program_images');
        $this->attachSeo($program, $data);

        Cache::forget('dashboard.programs_count');

        return $program;
    }

    public function update(Program $program, array $data): Program
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $program->update($data);

        $this->attachMedia($program, $image, $externalLink, $removeMedia, 'program_images');
        $this->attachSeo($program, $data);

        return $program;
    }


    public function delete(Program $program): bool
    {
        // امنع الحذف لو فيه مشاريع مرتبطة (سلامة البيانات)
        if ($program->projects()->exists()) {
            throw new \Exception('لا يمكن حذف البرنامج لوجود مشاريع مرتبطة به');
        }

        Cache::forget('dashboard.programs_count');
        return $program->delete();
    }
}