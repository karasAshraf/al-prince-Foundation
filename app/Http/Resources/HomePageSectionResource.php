<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'type'           => $this->type,
            'title_ar'       => $this->title_ar,
            'title_en'       => $this->title_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'image_url'      => \App\Helpers\MediaHelper::url($this->resource, 'home_section_images', 'image'),
            'extra_link'     => $this->extra_link,
            'label_ar'       => $this->label_ar,
            'label_en'       => $this->label_en,
            'label'          => $this->label,
            'data'           => $this->data,
            'counter_number' => $this->counter_number,
            'counter_icon'   => $this->counter_icon,
            'order'          => $this->order,
            'is_active'      => $this->is_active,
            'created_at'     => $this->created_at?->toDateTimeString(),
            'updated_at'     => $this->updated_at?->toDateTimeString(),
        ];
    }
}
