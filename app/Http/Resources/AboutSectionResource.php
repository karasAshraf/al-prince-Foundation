<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title_ar'       => $this->title_ar,
            'title_en'       => $this->title_en,
            'slug'           => $this->slug,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'status'         => $this->status,
            'image_url'      => \App\Helpers\MediaHelper::url($this->resource, 'about_images', 'image'),
            'video'          => $this->video,
            'external_link'  => $this->external_link,
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
