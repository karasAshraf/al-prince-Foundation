<?php

namespace App\Services;

use App\Models\GovernanceDocument;

class GovernanceDocumentService extends BaseService
{
    public function list(array $filters = [])
    {
        return GovernanceDocument::query()
            ->select('id', 'title_ar', 'title_en', 'category', 'fiscal_year', 'file_path', 'file_size', 'is_active', 'order', 'created_at', 'updated_at')
            ->with('media')
            ->when($filters['fiscal_year'] ?? null, fn($q, $y) => $q->where('fiscal_year', $y))
            ->when($filters['category'] ?? null, fn($q, $c) => $q->where('category', $c))
            ->orderByDesc('fiscal_year')
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): GovernanceDocument
    {
        $file = $data['file'] ?? $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['file'], $data['image'], $data['remove_media']);

        $document = GovernanceDocument::create($data);

        if ($removeMedia) {
            $document->clearMediaCollection('governance_files');
            $document->update(['file_path' => null, 'file_size' => null]);
        } elseif ($file) {
            $fileSize = $file->getSize();
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $safeFileName = \Illuminate\Support\Str::uuid() . '.' . strtolower($extension);
            $media = $document->addMedia($file)->usingFileName($safeFileName)->toMediaCollection('governance_files');
            $document->update([
                'file_path' => $media->getUrl(),
                'file_size' => $fileSize,
            ]);
        } elseif ($externalLink) {
            $document->clearMediaCollection('governance_files');
            $document->update([
                'file_path' => $externalLink,
                'file_size' => null,
            ]);
        }

        return $document;
    }

    public function update(GovernanceDocument $document, array $data): GovernanceDocument
    {
        $file = $data['file'] ?? $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['file'], $data['image'], $data['remove_media']);

        $document->update($data);

        if ($removeMedia) {
            $document->clearMediaCollection('governance_files');
            $document->update(['file_path' => null, 'file_size' => null]);
        } elseif ($file) {
            $fileSize = $file->getSize();
            $document->clearMediaCollection('governance_files');
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $safeFileName = \Illuminate\Support\Str::uuid() . '.' . strtolower($extension);
            $media = $document->addMedia($file)->usingFileName($safeFileName)->toMediaCollection('governance_files');
            $document->update([
                'file_path' => $media->getUrl(),
                'file_size' => $fileSize,
            ]);
        } elseif ($externalLink) {
            $document->clearMediaCollection('governance_files');
            $document->update([
                'file_path' => $externalLink,
                'file_size' => null,
            ]);
        }

        return $document;
    }


    public function delete(GovernanceDocument $document): bool
    {
        return $document->delete();
    }

    public function groupedForDisplay(?int $year = null)
    {
        return GovernanceDocument::active()
            ->when($year, fn($q) => $q->year($year))
            ->orderByDesc('fiscal_year')
            ->with('media')
            ->get()
            ->groupBy('category');
    }
}