<?php
/**
 * End-to-end async upload test:
 * 1. Create a test News record
 * 2. Attach a test image via addMedia (no sync conversion)
 * 3. Verify a job was queued in jobs table
 * 4. Record HTTP-response time (should be fast)
 * 5. Run queue worker to process
 * 6. Verify conversion files exist on disk
 * 7. Verify original is unchanged
 * 8. Clean up test record
 */
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// ── 0. Queue config confirmation ──────────────────────────────────────────
echo "=== Queue Configuration ===\n";
echo "QUEUE_CONNECTION (env):                " . env('QUEUE_CONNECTION', '(not set)') . "\n";
echo "queue.default (runtime):               " . config('queue.default') . "\n";
echo "media-library.queue_conversions_by_default: " . (config('media-library.queue_conversions_by_default') ? 'true' : 'false') . "\n";
echo "media-library.queue_connection_name:   " . config('media-library.queue_connection_name') . "\n";
echo "media-library.queue_conversions_after_database_commit: " . (config('media-library.queue_conversions_after_database_commit') ? 'true' : 'false') . "\n";
echo "\n";

// ── 1. Create test News record ────────────────────────────────────────────
echo "=== Step 1: Create Test News Record ===\n";

$news = \App\Models\News::create([
    'title_ar'     => 'اختبار تحميل الصورة - ' . now()->format('H:i:s'),
    'title_en'     => 'Image Upload Test - ' . now()->format('H:i:s'),
    'slug'         => 'upload-test-' . now()->timestamp,
    'content_ar'   => 'محتوى اختبار',
    'content_en'   => 'Test content',
    'excerpt_ar'   => 'ملخص اختبار',
    'excerpt_en'   => 'Test excerpt',
    'status'       => 'draft',
    'published_at' => now(),
]);
echo "Created News ID: {$news->id}\n";

// ── 2. Create a test PNG image ────────────────────────────────────────────
$testImgPath = sys_get_temp_dir() . '/test_upload_' . uniqid() . '.png';
$img = imagecreate(800, 600);
$bg = imagecolorallocate($img, 34, 120, 60); // brand green
$fg = imagecolorallocate($img, 255, 255, 255);
imagestring($img, 5, 200, 280, 'Al-Athar Async Upload Test', $fg);
imagepng($img, $testImgPath);
imagedestroy($img);
echo "Test image created: {$testImgPath} (" . round(filesize($testImgPath)/1024, 1) . " KB)\n";

// ── 3. Snapshot jobs table BEFORE upload ─────────────────────────────────
$jobsBefore = DB::table('jobs')->count();
echo "\nJobs in queue BEFORE upload: {$jobsBefore}\n";

// ── 4. Add media — time how long it takes ────────────────────────────────
echo "\n=== Step 2: Upload (addMedia) ===\n";
$start = microtime(true);

$media = $news->addMedia($testImgPath)
    ->usingFileName('test-upload-' . now()->timestamp . '.png')
    ->toMediaCollection('news_images');

$uploadMs = round((microtime(true) - $start) * 1000);
echo "addMedia() completed in: {$uploadMs}ms\n";
echo "Media ID: {$media->id}\n";
echo "File saved to: {$media->getPath()}\n";
echo "File exists on disk: " . (file_exists($media->getPath()) ? 'YES' : 'NO') . "\n";
echo "Original size: " . round(filesize($media->getPath()) / 1024, 1) . " KB\n";

// ── 5. Verify conversions NOT yet generated (async) ──────────────────────
$media->refresh();
$genConv = $media->generated_conversions ?? [];
echo "\nConversions immediately after upload: " . json_encode($genConv) . "\n";
echo "(Should be empty — they are queued, not yet processed)\n";

// ── 6. Verify a job was queued ────────────────────────────────────────────
echo "\n=== Step 3: Verify Queue Job Was Dispatched ===\n";
$jobsAfter = DB::table('jobs')->count();
echo "Jobs in queue AFTER upload:  {$jobsAfter}\n";
echo "New jobs dispatched:         " . ($jobsAfter - $jobsBefore) . "\n";

$queuedJob = DB::table('jobs')->orderByDesc('id')->first();
if ($queuedJob) {
    $payload = json_decode($queuedJob->payload, true);
    echo "Job class:  " . ($payload['displayName'] ?? 'unknown') . "\n";
    echo "Job queue:  " . ($queuedJob->queue ?? 'default') . "\n";
}

// ── 7. Run queue worker to process ───────────────────────────────────────
echo "\n=== Step 4: Process Queue Worker ===\n";
echo "Running: php artisan queue:work --stop-when-empty --tries=3\n";
$workerOutput = shell_exec('php artisan queue:work --stop-when-empty --tries=3 --env=local 2>&1');
echo $workerOutput . "\n";

// ── 8. Verify conversions on disk ────────────────────────────────────────
echo "=== Step 5: Verify Conversion Files on Disk ===\n";
$media->refresh();
$genConv = $media->generated_conversions ?? [];
echo "DB generated_conversions: " . json_encode(array_keys($genConv)) . "\n";

$convDir = dirname($media->getPath()) . DIRECTORY_SEPARATOR . 'conversions';
$originalPath = $media->getPath();

if (is_dir($convDir)) {
    $files = array_values(array_diff(scandir($convDir), ['.', '..']));
    echo "Conversion files on disk:\n";
    foreach ($files as $f) {
        $fp = $convDir . DIRECTORY_SEPARATOR . $f;
        echo "  {$f}  (" . round(filesize($fp)/1024, 1) . " KB)\n";
    }
} else {
    echo "Conversion directory missing: {$convDir}\n";
}

// ── 9. Verify original unchanged ─────────────────────────────────────────
echo "\n=== Step 6: Verify Original File Unchanged ===\n";
echo "Original path:  {$originalPath}\n";
echo "Original exists: " . (file_exists($originalPath) ? 'YES' : 'NO') . "\n";
echo "Original size:  " . (file_exists($originalPath) ? round(filesize($originalPath)/1024, 1) . ' KB' : 'N/A') . "\n";
echo "Original MIME:  " . (file_exists($originalPath) ? mime_content_type($originalPath) : 'N/A') . "\n";

// ── 10. Test MediaHelper::url() returns conversion URL ───────────────────
echo "\n=== Step 7: Verify Frontend URL Resolution ===\n";
$news->refresh();
$rawUrl    = \App\Helpers\MediaHelper::url($news, 'news_images', 'image');
$thumbUrl  = \App\Helpers\MediaHelper::url($news, 'news_images', 'image', 'thumb');
$cardUrl   = \App\Helpers\MediaHelper::url($news, 'news_images', 'image', 'card');
$detailUrl = \App\Helpers\MediaHelper::url($news, 'news_images', 'image', 'detail');

echo "Raw URL:    {$rawUrl}\n";
echo "Thumb URL:  {$thumbUrl}\n";
echo "Card URL:   {$cardUrl}\n";
echo "Detail URL: {$detailUrl}\n";
echo "Uses WebP:  " . (str_ends_with($detailUrl ?? '', '.webp') ? 'YES ✓' : 'NO (fallback to original)') . "\n";

// ── 11. Clean up ──────────────────────────────────────────────────────────
echo "\n=== Step 8: Cleanup ===\n";
$news->clearMediaCollection('news_images');
$news->delete();
echo "Test record deleted.\n";
echo "\n=== DONE ===\n";
