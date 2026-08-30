<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title_ar'       => $this->title_ar,
            'title_en'       => $this->title_en,
            'slug'           => $this->slug,
            'excerpt_ar'     => $this->summary_ar ?? null,
            'excerpt_en'     => $this->summary_en ?? null,
            'summary_ar'     => $this->summary_ar ?? null,
            'summary_en'     => $this->summary_en ?? null,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'status'         => $this->status,
            'order'          => $this->order,
            'image_url'      => \App\Helpers\MediaHelper::url($this->resource, 'program_images', 'image'),
            'external_link'  => $this->external_link,
            'projects_count' => $this->when($this->relationLoaded('projects'), fn() => $this->projects->count()),
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
