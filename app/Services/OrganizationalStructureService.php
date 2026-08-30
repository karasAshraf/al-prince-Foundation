<?php

namespace App\Services;

use App\Models\OrganizationalStructure;
use Illuminate\Support\Facades\Cache;

class OrganizationalStructureService extends BaseService
{
    /**
     * Return the active organizational structure record,
     * or the first record if no active one exists.
     * Result is cached for 24 hours.
     */
    public function getActive(): ?OrganizationalStructure
    {
        return Cache::remember('organizational_structure.active', now()->addHours(24), function () {
            return OrganizationalStructure::with('media')
                ->where('is_active', true)
                ->first()
                ?? OrganizationalStructure::with('media')->first();
        });
    }

    /**
     * Return the first record (for admin edit form), creating one if none exists.
     */
    public function getFirstRecord(): OrganizationalStructure
    {
        return OrganizationalStructure::with('media')->first()
            ?? OrganizationalStructure::create([
                'title_ar'  => 'الهيكل التنظيمي',
                'title_en'  => 'Organizational Structure',
                'is_active' => true,
            ]);
    }

    /**
     * Update the single organizational structure record.
     * Handles bilingual image attachments and cache invalidation.
     */
    public function update(array $data): OrganizationalStructure
    {
        $structure = $this->getFirstRecord();

        $imageAr      = $data['image_ar']       ?? null;
        $imageEn      = $data['image_en']       ?? null;
        $removeAr     = !empty($data['remove_image_ar']);
        $removeEn     = !empty($data['remove_image_en']);

        unset($data['image_ar'], $data['image_en'], $data['remove_image_ar'], $data['remove_image_en']);

        $structure->update($data);

        // Handle Arabic chart image
        $this->attachMedia(
            $structure,
            $imageAr,
            null,
            $removeAr,
            'organizational_structure_ar',
            'image_ar'
        );

        // Handle English chart image
        $this->attachMedia(
            $structure,
            $imageEn,
            null,
            $removeEn,
            'organizational_structure_en',
            'image_en'
        );

        // Invalidate cache so next frontend request picks up new data
        Cache::forget('organizational_structure.active');

        return $structure->fresh(['media']);
    }
}
