<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name_ar'     => $this->name_ar,
            'name_en'     => $this->name_en,
            'position_ar' => $this->position_ar,
            'position_en' => $this->position_en,
            'bio_ar'      => $this->bio_ar,
            'bio_en'      => $this->bio_en,
            'type'        => $this->type,
            'image_url'   => \App\Helpers\MediaHelper::url($this->resource, 'team_photos', 'image'),
            'order'       => $this->order,
            'is_active'   => $this->is_active,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
