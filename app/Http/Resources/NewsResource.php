<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title_ar'      => $this->title_ar,
            'title_en'      => $this->title_en,
            'slug'          => $this->slug,
            'excerpt_ar'    => $this->excerpt_ar,
            'excerpt_en'    => $this->excerpt_en,
            'content_ar'    => $this->when(
                $request->routeIs('api.news.show'),
                $this->content_ar
            ),
            'content_en'    => $this->when(
                $request->routeIs('api.news.show'),
                $this->content_en
            ),
            'status'        => $this->status,
            'image_url'     => \App\Helpers\MediaHelper::url($this->resource, 'news_images', 'image'),
            'external_link' => $this->external_link,
            'published_at'  => $this->published_at?->toDateTimeString(),
            'author'        => $this->when($this->relationLoaded('author'), fn() => [
                'id'   => $this->author?->id,
                'name' => $this->author?->name,
            ]),
            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
        ];
    }
}
