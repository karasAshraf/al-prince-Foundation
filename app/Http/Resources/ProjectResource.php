<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'program_id'     => $this->program_id,
            'program'        => $this->when($this->relationLoaded('program'), fn() => [
                'id'       => $this->program?->id,
                'title_ar' => $this->program?->title_ar,
                'title_en' => $this->program?->title_en,
            ]),
            'title_ar'       => $this->title_ar,
            'title_en'       => $this->title_en,
            'slug'           => $this->slug,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'goal_ar'        => $this->goal_ar,
            'goal_en'        => $this->goal_en,
            'status'         => $this->status,
            'project_status' => $this->project_status,
            'start_date'     => $this->start_date?->toDateString(),
            'end_date'       => $this->end_date?->toDateString(),
            'image_url'      => \App\Helpers\MediaHelper::url($this->resource, 'project_images', 'image'),
            'external_link'  => $this->external_link,
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
