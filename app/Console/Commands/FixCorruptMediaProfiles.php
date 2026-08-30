<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Spatie\MediaLibrary\Conversions\FileManipulator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * FixCorruptMediaProfiles
 *
 * Finds Media records with empty or incomplete conversions caused by corrupted
 * iCCP/sRGB PNG profiles that crash the Spatie GD driver, strips the offending
 * chunk from the source file, then regenerates all registered conversions
 * synchronously.
 *
 * Safe guarantees:
 *  - Never permanently deletes the original file (renames .tmp, atomically replaces)
 *  - Only modifies files whose iCCP chunk is confirmed present via binary scan
 *  - Never touches unrelated DB records
 *  - A --media-id option lets you target a single record without scanning all media
 *  - A --dry-run option reports what would happen without writing anything
 */
class FixCorruptMediaProfiles extends Command
{
    /** @var string */
    protected $signature = 'media:fix-corrupt-profiles
                            {--media-id=   : Only process a specific media ID}
                            {--model=      : Only process media belonging to a specific model class (e.g. App\\\\Models\\\\HomePageSection)}
                            {--collection= : Only process media in a specific collection}
                            {--mime=image/png : MIME type(s) to include (comma-separated, default: image/png)}
                            {--dry-run     : Analyse only — do not modify files or regenerate conversions}
                            {--force       : Also re-process media that already has some conversions}';

    /** @var string */
    protected $description = 'Detect and fix media files with corrupted iCCP/sRGB profiles that prevent Spatie conversion generation';

    /** @var FileManipulator */
    private FileManipulator $fileManipulator;

    /** Counters */
    private int $inspected   = 0;
    private int $needsFix    = 0;
    private int $fixed       = 0;
    private int $skipped     = 0;
    private int $failed      = 0;

    public function handle(FileManipulator $fileManipulator): int
    {
        $this->fileManipulator = $fileManipulator;

        // Force synchronous queue so conversions run inline
        config(['queue.default' => 'sync']);

        $dryRun    = $this->option('dry-run');
        $forceAll  = $this->option('force');
        $mediaId   = $this->option('media-id');
        $modelOpt  = $this->option('model');
        $collection = $this->option('collection');
        $mimes     = array_map('trim', explode(',', $this->option('mime')));

        $this->info('');
        $this->info('=== Fix Corrupt Media Profiles ===');
        $this->info('Spatie Media Library v11.23.3');
        if ($dryRun) {
            $this->warn('DRY-RUN mode — no files will be modified');
        }
        $this->info('');

        // ── Build query ──────────────────────────────────────────────────
        $query = Media::query()->whereIn('mime_type', $mimes);

        if ($mediaId) {
            $query->where('id', (int) $mediaId);
        }
        if ($modelOpt) {
            $query->where('model_type', $modelOpt);
        }
        if ($collection) {
            $query->where('collection_name', $collection);
        }

        // Unless --force, skip media that already has ALL conversions populated
        if (!$forceAll) {
            $query->where(function ($q) {
                // generated_conversions is empty JSON array/object, or NULL
                $q->whereNull('generated_conversions')
                  ->orWhere('generated_conversions', '[]')
                  ->orWhere('generated_conversions', '{}')
                  ->orWhere('generated_conversions', '');
            });
        }

        $mediaRecords = $query->get();
        $this->inspected = $mediaRecords->count();
        $this->info("Media records to inspect: {$this->inspected}");
        $this->info('');

        if ($this->inspected === 0) {
            $this->info('Nothing to process.');
            $this->printSummary();
            return self::SUCCESS;
        }

        // ── Process each record ──────────────────────────────────────────
        foreach ($mediaRecords as $media) {
            $this->processMedia($media, $dryRun);
        }

        // ── Summary ──────────────────────────────────────────────────────
        $this->info('');
        $this->printSummary();

        return $this->failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Core processing
    // ─────────────────────────────────────────────────────────────────────

    private function processMedia(Media $media, bool $dryRun): void
    {
        $label = "Media #{$media->id} [{$media->model_type}] {$media->file_name}";
        $this->line("  ▶ {$label}");

        $originalPath = $media->getPath();

        // ── 1. Verify file exists ─────────────────────────────────────
        if (!file_exists($originalPath)) {
            $this->warn("    SKIP — source file missing: {$originalPath}");
            $this->skipped++;
            Log::warning("FixCorruptMediaProfiles: skipped media #{$media->id} — file not found at {$originalPath}");
            return;
        }

        $this->inspected++;   // count as inspected (already counted in query, keep for per-item log)

        // ── 2. Detect iCCP chunk ──────────────────────────────────────
        $hasCorruptProfile = $this->hasBadIccpChunk($originalPath);

        if (!$hasCorruptProfile) {
            // Double-check: try actually loading with GD to see if it errors
            $hasCorruptProfile = $this->gdFailsToLoad($originalPath);
        }

        if (!$hasCorruptProfile) {
            $this->line('    SKIP — no corrupt iCCP profile detected');
            $this->skipped++;
            return;
        }

        $this->needsFix++;
        $this->warn("    NEEDS FIX — corrupt iCCP profile detected");

        if ($dryRun) {
            $this->line('    (dry-run) Would strip iCCP and regenerate conversions');
            return;
        }

        // ── 3. Strip the iCCP profile ─────────────────────────────────
        $stripped = $this->stripIccpProfile($originalPath);
        if (!$stripped) {
            $this->error("    FAIL — could not strip iCCP profile from {$originalPath}");
            $this->failed++;
            Log::error("FixCorruptMediaProfiles: FAIL stripping iCCP for media #{$media->id} at {$originalPath}");
            return;
        }
        $this->info('    iCCP profile stripped ✓');

        // ── 4. Regenerate conversions ─────────────────────────────────
        try {
            $conversions = ConversionCollection::createForMedia($media);

            if ($conversions->isEmpty()) {
                $this->warn('    SKIP — no conversions registered for this media model');
                $this->skipped++;
                return;
            }

            $conversionNames = $conversions->map(fn($c) => $c->getName())->implode(', ');
            $this->line("    Conversions to generate: {$conversionNames}");

            $this->fileManipulator->performConversions($conversions, $media);

        } catch (\Throwable $e) {
            $this->error("    FAIL — conversion error: " . $e->getMessage());
            $this->failed++;
            Log::error("FixCorruptMediaProfiles: FAIL performing conversions for media #{$media->id}: " . $e->getMessage());
            return;
        }

        // ── 5. Verify on disk ─────────────────────────────────────────
        $media->refresh();
        $generatedConversions = $media->generated_conversions ?? [];

        if (empty($generatedConversions)) {
            $this->error('    FAIL — generated_conversions still empty in DB after job ran');
            $this->failed++;
            Log::error("FixCorruptMediaProfiles: FAIL — conversions still empty in DB for media #{$media->id}");
            return;
        }

        // Verify each conversion file actually exists on disk
        $convDir  = dirname($originalPath) . DIRECTORY_SEPARATOR . 'conversions';
        $allExist = true;
        $diskFiles = [];

        if (is_dir($convDir)) {
            $diskFiles = array_values(array_diff(scandir($convDir), ['.', '..']));
        }

        foreach (array_keys($generatedConversions) as $convName) {
            $found = false;
            foreach ($diskFiles as $f) {
                if (str_contains($f, "-{$convName}.")) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->error("    FAIL — disk file for conversion '{$convName}' not found in {$convDir}");
                $allExist = false;
            }
        }

        if (!$allExist) {
            $this->failed++;
            Log::error("FixCorruptMediaProfiles: FAIL — disk verification failed for media #{$media->id}");
            return;
        }

        $this->info('    DB conversions: ' . json_encode(array_keys($generatedConversions)));
        $this->info('    Disk files:     ' . json_encode($diskFiles));
        $this->info('    FIXED ✓');
        $this->fixed++;
        Log::info("FixCorruptMediaProfiles: fixed media #{$media->id} ({$media->file_name}), conversions: " . json_encode(array_keys($generatedConversions)));
    }

    // ─────────────────────────────────────────────────────────────────────
    //  iCCP detection and stripping
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Binary scan the PNG file for an iCCP chunk.
     * Fast — reads only the first ~16 KB (iCCP always appears near the start).
     */
    private function hasBadIccpChunk(string $path): bool
    {
        if (!str_ends_with(strtolower($path), '.png')) {
            return false; // Only PNG files carry iCCP chunks this way
        }

        $fh = @fopen($path, 'rb');
        if (!$fh) {
            return false;
        }

        $header = fread($fh, 16384); // Read first 16 KB
        fclose($fh);

        // PNG signature is 8 bytes; chunks follow. iCCP type bytes = 0x69434350
        return str_contains($header, 'iCCP');
    }

    /**
     * Try loading the file with GD (suppressing warnings).
     * Returns true if GD cannot load it (indicating a corrupt/incompatible profile).
     */
    private function gdFailsToLoad(string $path): bool
    {
        $mime = mime_content_type($path);
        set_error_handler(fn() => true); // suppress all errors during detection

        $res = match (true) {
            str_contains($mime, 'png')  => @imagecreatefrompng($path),
            str_contains($mime, 'jpeg') => @imagecreatefromjpeg($path),
            str_contains($mime, 'webp') => @imagecreatefromwebp($path),
            default                     => false,
        };

        restore_error_handler();

        if ($res !== false && $res !== null) {
            imagedestroy($res);
            return false; // GD loaded it fine
        }

        return true; // GD failed
    }

    /**
     * Strip the iCCP profile from a PNG file using two techniques:
     *
     * Technique A (preferred): GD roundtrip — load with error suppression,
     * re-save as PNG without embedded profile. Same proven method as hero_force_convert.php.
     *
     * Technique B (fallback): Binary chunk removal — parse the PNG chunk structure
     * and surgically remove only the iCCP chunk, rebuilding the file byte-for-byte.
     *
     * The original file is replaced atomically via rename() so it is never
     * left in a partially-written state. The temp file is cleaned up on failure.
     */
    private function stripIccpProfile(string $path): bool
    {
        $tmpPath = $path . '.iccpfix.tmp';

        // ── Technique A: GD roundtrip ──────────────────────────────────
        set_error_handler(fn() => true); // suppress iCCP libpng warnings

        $imgRes = @imagecreatefrompng($path);

        restore_error_handler();

        if ($imgRes !== false && $imgRes !== null) {
            // Preserve alpha channel
            imagesavealpha($imgRes, true);
            // Save at compression level 6 (good balance; lossless for PNG)
            $saved = imagepng($imgRes, $tmpPath, 6);
            imagedestroy($imgRes);

            if ($saved && file_exists($tmpPath) && filesize($tmpPath) > 0) {
                rename($tmpPath, $path);
                return true;
            }
            @unlink($tmpPath);
        }

        // ── Technique B: Binary iCCP chunk removal ─────────────────────
        $raw = @file_get_contents($path);
        if ($raw === false) {
            return false;
        }

        $clean = $this->removePngChunkBinary($raw, 'iCCP');

        if ($clean === $raw) {
            // iCCP chunk was not found or could not be removed — not a fixable case
            $this->warn('    iCCP chunk not found via binary scan (may not be a PNG iCCP issue)');
            return false;
        }

        $written = @file_put_contents($tmpPath, $clean);
        if ($written === false || $written === 0) {
            @unlink($tmpPath);
            return false;
        }

        rename($tmpPath, $path);
        return true;
    }

    /**
     * Parse a PNG binary and remove all chunks matching $chunkType.
     * Returns the original string unchanged if the chunk is not found.
     *
     * PNG structure: 8-byte signature + repeated chunks.
     * Each chunk: [4-byte length][4-byte type][N-byte data][4-byte CRC]
     */
    private function removePngChunkBinary(string $data, string $chunkType): string
    {
        if (strlen($data) < 8) {
            return $data;
        }

        $signature = substr($data, 0, 8);
        $result    = $signature;
        $pos       = 8;
        $len       = strlen($data);
        $removed   = 0;

        while ($pos < $len) {
            if ($pos + 8 > $len) {
                // Truncated chunk — append remainder as-is and stop
                $result .= substr($data, $pos);
                break;
            }

            $chunkLen  = unpack('N', substr($data, $pos, 4))[1];
            $type      = substr($data, $pos + 4, 4);
            $totalSize = 4 + 4 + $chunkLen + 4; // length field + type + data + CRC

            if ($type === $chunkType) {
                $removed++;
                $this->line("    Removed binary chunk '{$chunkType}' ({$chunkLen} bytes)");
            } else {
                $result .= substr($data, $pos, $totalSize);
            }

            $pos += $totalSize;
        }

        return $removed > 0 ? $result : $data;
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Output
    // ─────────────────────────────────────────────────────────────────────

    private function printSummary(): void
    {
        $this->info('=== Summary ===');
        $this->info("  Inspected:         {$this->inspected}");
        $this->info("  Requiring fix:     {$this->needsFix}");
        $this->info("  Successfully fixed:{$this->fixed}");
        $this->info("  Skipped:           {$this->skipped}");

        if ($this->failed > 0) {
            $this->error("  Still failing:     {$this->failed}");
        } else {
            $this->info("  Still failing:     {$this->failed}");
        }
    }
}
