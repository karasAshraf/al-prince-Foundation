<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProjectService extends BaseService
{
    public function list(array $filters = [])
    {
        return Project::query()
            ->select('id', 'program_id', 'title_ar', 'title_en', 'slug', 'project_status', 'status', 'external_link', 'start_date', 'end_date', 'created_at', 'updated_at')
            ->with(['program', 'media'])
            ->when($filters['program_id'] ?? null, fn($q, $id) => $q->where('program_id', $id))
            ->when($filters['project_status'] ?? null, fn($q, $s) => $q->where('project_status', $s))
            ->latest()
            ->paginate(15);
    }

    public function create(array $data): Project
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $data['slug'] = $data['slug'] ?? Str::slug($data['title_ar']);

        $project = Project::create($data);

        $this->attachMedia($project, $image, $externalLink, $removeMedia, 'project_images');
        $this->attachSeo($project, $data);

        Cache::forget('dashboard.projects_count');

        return $project;
    }

    public function update(Project $project, array $data): Project
    {
        $image = $data['image'] ?? null;
        $externalLink = !empty($data['external_link']) ? $data['external_link'] : ($data['media_external_link'] ?? null);
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['remove_media'], $data['media_external_link']);
        $data['external_link'] = $externalLink;

        $project->update($data);

        $this->attachMedia($project, $image, $externalLink, $removeMedia, 'project_images');
        $this->attachSeo($project, $data);

        return $project;
    }


    public function delete(Project $project): bool
    {
        Cache::forget('dashboard.projects_count');
        return $project->delete();
    }

    // مشاريع برنامج معين — تستخدم في صفحة البرنامج نفسه
    public function byProgram(int $programId)
    {
        return Project::where('program_id', $programId)->published()->get();
    }
}