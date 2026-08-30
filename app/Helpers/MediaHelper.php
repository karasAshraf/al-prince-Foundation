<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    /**
     * Resolve a displayable URL for any model's media.
     *
     * Priority order:
     *   1. Spatie MediaLibrary collection  → uses getFirstMediaUrl($collection)
     *   2. Model's image column            → converts stored value to URL
     *   3. Fallback null                   → caller shows placeholder
     *
     * The image column may contain:
     *   - A full absolute URL  (http://…) — returned as-is
     *   - A storage-relative path (39/filename.png or /storage/39/filename.png)
     *   - A path with leading /storage/    — stripped then re-prefixed via Storage::url()
     *
     * @param  Model|null  $model
     * @param  string      $collection  Spatie collection name (e.g. 'project_images')
     * @param  string      $column      Image column on the model (default: 'image')
     * @return string|null
     */
    public static function url(?Model $model, string $collection = 'default', string $column = 'image', string $conversion = ''): ?string
    {
        if (!$model) {
            return null;
        }

        // 1. Try Spatie MediaLibrary first (cleanest source of truth)
        if (method_exists($model, 'getFirstMediaUrl')) {
            $media = method_exists($model, 'getFirstMedia') ? $model->getFirstMedia($collection) : null;
            if ($media) {
                // Check if custom property holds an external URL
                $customExt = $media->getCustomProperty('external_url');
                if (!empty($customExt)) {
                    return static::resolveUrl($customExt);
                }

                if (!empty($conversion) && $media->hasGeneratedConversion($conversion)) {
                    try {
                        $mediaUrl = $media->getUrl($conversion);
                    } catch (\Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidConversion $e) {
                        $mediaUrl = $media->getUrl();
                    }
                } else {
                    $mediaUrl = $media->getUrl();
                }
                if (!empty($mediaUrl)) {
                    return static::resolveUrl($mediaUrl);
                }
            }
        }

        // 2. Fallback to database column (e.g. 'image')
        $value = $model->getAttribute($column);
        if (!empty($value)) {
            return static::resolveUrl($value);
        }

        // 3. Fallback to external_link / extra_link / media_external_link attributes
        foreach (['external_link', 'extra_link', 'media_external_link'] as $attr) {
            $ext = $model->getAttribute($attr);
            if (!empty($ext)) {
                return static::resolveUrl($ext);
            }
        }

        return null;
    }

    /**
     * Convert any stored image value to a browser-accessible URL.
     *
     * Handles:
     *   - Absolute URLs (http:// or https://)  → returned as-is (Storage::url is NOT called)
     *   - Data URIs (data:image/...)           → returned as-is
     *   - Paths starting with /storage/         → strip prefix, use Storage::url()
     *   - Bare storage-relative paths (39/f.png) → use Storage::url()
     *
     * @param  string|null $value
     * @return string|null
     */
    public static function resolveUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // Already a full external or absolute URL (http:// or https://) → return as-is
        if (preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        // Data URIs
        if (str_starts_with($value, 'data:')) {
            return $value;
        }

        // Strip a leading /storage/ or storage/ prefix so Storage::url() doesn't double it
        if (str_starts_with($value, '/storage/')) {
            $value = ltrim(substr($value, 9), '/');
        } elseif (str_starts_with($value, 'storage/')) {
            $value = ltrim(substr($value, 8), '/');
        }

        // Generate URL via Storage facade for local filesystem paths
        return Storage::disk('public')->url($value);
    }

    /**
     * Determine whether a URL string points to an external resource
     * (i.e. hosted on a different domain than the application).
     *
     * Used by Blade components to decide which tab to show in media-upload.
     *
     * @param  string|null $url
     * @return bool
     */
    public static function isExternal(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        // If it's not an absolute URL at all it must be a local path
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return false;
        }

        $appHost = parse_url(config('app.url', ''), PHP_URL_HOST);
        $valueHost = parse_url($url, PHP_URL_HOST);

        if (!$appHost || !$valueHost) {
            return true; // unable to parse — treat as external to be safe
        }

        // Same host → local (Spatie-generated URL or asset URL)
        return $appHost !== $valueHost;
    }

    /**
     * Return the storage-relative path to store in the database.
     * Strips the public-disk root so only "39/filename.png" is stored.
     *
     * @param  string $fullPath  absolute filesystem path from addMedia
     * @return string
     */
    public static function storagePath(string $fullPath): string
    {
        $publicRoot = Storage::disk('public')->path('');
        if (str_starts_with($fullPath, $publicRoot)) {
            return ltrim(str_replace($publicRoot, '', $fullPath), DIRECTORY_SEPARATOR . '/');
        }
        return $fullPath;
    }

    /**
     * Check if a given URL string points to an image resource.
     *
     * @param  string|null $url
     * @return bool
     */
    public static function isImageUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $url = trim($url);

        if (str_starts_with($url, 'data:image/')) {
            return true;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $cleanPath = strtolower(trim($path));

        if (preg_match('/\.(jpg|jpeg|png|webp|gif|svg|bmp|tiff?|ico|avif)$/i', $cleanPath)) {
            return true;
        }

        $lowerUrl = strtolower($url);

        if (str_contains($lowerUrl, 'cloudinary.com') && str_contains($lowerUrl, '/image/upload/')) {
            return true;
        }

        if (preg_match('/(images\.unsplash\.com|i\.imgur\.com|i\.postimg\.cc|cdn\.pixabay\.com|images\.pexels\.com|img\.youtube\.com)/i', $lowerUrl)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the external link associated with a model is being used as the image source.
     *
     * @param  Model|null   $model
     * @param  string|null  $externalLink
     * @param  string       $collection
     * @param  string       $column
     * @return bool
     */
    public static function isExternalLinkUsedAsImage(?Model $model, ?string $externalLink = null, string $collection = 'default', string $column = 'image'): bool
    {
        if (!$model) {
            return static::isImageUrl($externalLink);
        }

        if ($externalLink === null) {
            foreach (['external_link', 'extra_link', 'media_external_link'] as $attr) {
                $ext = $model->getAttribute($attr);
                if (!empty($ext)) {
                    $externalLink = $ext;
                    break;
                }
            }
        }

        if (empty($externalLink)) {
            return false;
        }

        $imageSrc = static::url($model, $collection, $column);
        if (!empty($imageSrc)) {
            if (trim(strtolower($imageSrc)) === trim(strtolower($externalLink))) {
                return true;
            }
        }

        return static::isImageUrl($externalLink);
    }

    /**
     * Determine whether an external website link should be displayed as a button or action link.
     * Returns true ONLY if the link is a legitimate external website link and NOT being used as an image source.
     *
     * @param  Model|null   $model
     * @param  string|null  $link
     * @param  string       $collection
     * @param  string       $column
     * @return bool
     */
    public static function shouldShowExternalLink(?Model $model = null, ?string $link = null, string $collection = 'default', string $column = 'image'): bool
    {
        if (empty($link) && $model) {
            foreach (['external_link', 'extra_link', 'media_external_link'] as $attr) {
                $ext = $model->getAttribute($attr);
                if (!empty($ext)) {
                    $link = $ext;
                    break;
                }
            }
        }

        if (empty($link)) {
            return false;
        }

        if ($model && static::isExternalLinkUsedAsImage($model, $link, $collection, $column)) {
            return false;
        }

        if (static::isImageUrl($link)) {
            return false;
        }

        return true;
    }
}
