<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

abstract class BaseService
{
    /**
     * Per-request cache of Schema::hasColumn() results.
     * Avoids repeated SHOW COLUMNS queries on every attachMedia() call.
     */
    private static array $columnCache = [];

    /**
     * Check if a table has a column, caching the result for the request lifetime.
     */
    private function hasColumn(string $table, string $column): bool
    {
        $key = "{$table}.{$column}";
        if (!array_key_exists($key, self::$columnCache)) {
            self::$columnCache[$key] = Schema::hasColumn($table, $column);
        }
        return self::$columnCache[$key];
    }

    /**
     * Attach image / video file OR external media link to a model.
     *
     * DATABASE CONTRACT
     * ─────────────────
     * image column      → stores a storage-relative PATH (e.g. "39/file.png")
     *                     NEVER a full local URL (http://127.0.0.1:8000/storage/…)
     *                     NEVER an external URL (that belongs in external_link / extra_link)
     *
     * external_link / extra_link → stores the external URL the user typed.
     *
     * URL GENERATION
     * ──────────────
     * Use MediaHelper::url($model, $collection) everywhere in Blade.
     * Never call asset() directly on a column value.
     */
    protected function attachMedia(
        Model $model,
        ?UploadedFile $file = null,
        ?string $externalLink = null,
        bool $removeMedia = false,
        string $collection = 'default',
        string $imageColumn = 'image',
        string $externalLinkColumn = 'external_link'
    ): void {
        $updates = [];
        $table   = $model->getTable();

        // ── 1. Remove all media ──────────────────────────────────────────────
        if ($removeMedia) {
            if (method_exists($model, 'clearMediaCollection')) {
                try { $model->clearMediaCollection($collection); } catch (\Throwable) {}
            }

            if ($this->hasColumn($table, $imageColumn)) {
                $updates[$imageColumn] = null;
            }
            if ($this->hasColumn($table, $externalLinkColumn)) {
                $updates[$externalLinkColumn] = null;
            }
            if ($externalLinkColumn !== 'extra_link' && $this->hasColumn($table, 'extra_link')) {
                $updates['extra_link'] = null;
            }

            if (!empty($updates)) {
                $model->update($updates);
            }

            return;
        }

        // ── 2. New file uploaded ─────────────────────────────────────────────
        if ($file) {
            if (method_exists($model, 'clearMediaCollection')) {
                try { $model->clearMediaCollection($collection); } catch (\Throwable) {}
            }

            if (method_exists($model, 'addMedia')) {
                // Spatie MediaLibrary: store RELATIVE path, NOT the full URL.
                // MediaHelper::url() calls getFirstMediaUrl() first, so the
                // image column is a fallback only (needed for legacy records).
                $media = $model->addMedia($file)->toMediaCollection($collection);

                // Spatie default path structure: "{media_id}/{file_name}"
                $relativePath = $media->id . '/' . $media->file_name;
            } else {
                // Plain Storage fallback (no Spatie) — path relative to disk root
                $relativePath = $file->store($collection, 'public');
            }

            if ($this->hasColumn($table, $imageColumn)) {
                $updates[$imageColumn] = $relativePath;
            }

            // Clear link columns when a real file is uploaded
            if ($this->hasColumn($table, $externalLinkColumn)) {
                $updates[$externalLinkColumn] = null;
            }
            if ($externalLinkColumn !== 'extra_link' && $this->hasColumn($table, 'extra_link')) {
                $updates['extra_link'] = null;
            }

            if (!empty($updates)) {
                $model->update($updates);
            }

            return;
        }

        // ── 3. External URL provided ─────────────────────────────────────────
        if (!empty($externalLink)) {
            $updates = [];

            // Always store/preserve the external URL in the designated database column or image column
            if ($this->hasColumn($table, $externalLinkColumn)) {
                $updates[$externalLinkColumn] = $externalLink;
            } elseif ($this->hasColumn($table, 'extra_link')) {
                $updates['extra_link'] = $externalLink;
            } elseif ($this->hasColumn($table, $imageColumn)) {
                $updates[$imageColumn] = $externalLink;
            }

            // Optionally, if the link points to a direct image file and Spatie MediaLibrary is supported,
            // also attach it for image previews without wiping the external_link column.
            if (method_exists($model, 'addMediaFromUrl')) {
                try {
                    if (method_exists($model, 'clearMediaCollection')) {
                        try { $model->clearMediaCollection($collection); } catch (\Throwable) {}
                    }
                    $media = $model->addMediaFromUrl($externalLink)->toMediaCollection($collection);
                    if ($media) {
                        $media->setCustomProperty('external_url', $externalLink)->save();
                        $relativePath = $media->id . '/' . $media->file_name;
                        if ($this->hasColumn($table, $imageColumn)) {
                            $updates[$imageColumn] = $relativePath;
                        }
                    }
                } catch (\Throwable) {
                    // Ignore media fetch failure (e.g. offline/timeout) — raw URL is retained in external_link or image column
                    if ($this->hasColumn($table, $imageColumn) && empty($updates[$imageColumn])) {
                        $updates[$imageColumn] = $externalLink;
                    }
                }
            }

            if (!empty($updates)) {
                $model->update($updates);
            }
        }
    }

    protected function attachImage(Model $model, ?UploadedFile $file, string $collection = 'default'): void
    {
        $this->attachMedia($model, $file, null, false, $collection);
    }

    protected function attachSeo(Model $model, array $data): void
    {
        $seoKeys = ['meta_title_ar', 'meta_title_en', 'meta_description_ar', 'meta_description_en', 'meta_keywords', 'canonical_url', 'og_image'];
        
        $hasSeoData = false;
        foreach ($seoKeys as $key) {
            if (array_key_exists($key, $data)) {
                $hasSeoData = true;
                break;
            }
        }

        if ($hasSeoData) {
            $model->seoMeta()->updateOrCreate(
                ['seo_metable_id' => $model->id, 'seo_metable_type' => get_class($model)],
                [
                    'meta_title_ar'       => $data['meta_title_ar'] ?? null,
                    'meta_title_en'       => $data['meta_title_en'] ?? null,
                    'meta_description_ar' => $data['meta_description_ar'] ?? null,
                    'meta_description_en' => $data['meta_description_en'] ?? null,
                    'meta_keywords'       => $data['meta_keywords'] ?? null,
                    'canonical_url'       => $data['canonical_url'] ?? null,
                    'og_image'            => $data['og_image'] ?? null,
                ]
            );
        }
    }
}
