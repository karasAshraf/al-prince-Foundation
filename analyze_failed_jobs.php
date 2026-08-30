<?php
// Decode all failed jobs to identify affected media IDs
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->orderBy('failed_at')->get();

echo "=== FAILED JOBS ANALYSIS ===\n";
echo "Total failed jobs: " . $failedJobs->count() . "\n\n";

foreach ($failedJobs as $job) {
    echo "--- Job DB ID: {$job->id} | UUID: {$job->uuid} ---\n";

    $payload = json_decode($job->payload, true);
    if (!$payload) {
        echo "  ERROR: Could not decode payload JSON\n";
        continue;
    }

    $commandSerialized = $payload['data']['command'] ?? null;
    if (!$commandSerialized) {
        echo "  ERROR: No command in payload\n";
        continue;
    }

    // Unserialize carefully
    try {
        $jobObj = unserialize($commandSerialized);
    } catch (\Throwable $e) {
        echo "  ERROR unserializing: " . $e->getMessage() . "\n";
        continue;
    }

    // Get the media property via reflection
    $mediaId = null;
    $modelType = null;
    $collectionName = null;

    try {
        $ref = new \ReflectionObject($jobObj);

        // Try to get 'media' property
        foreach ($ref->getProperties() as $prop) {
            $prop->setAccessible(true);
            $val = $prop->getValue($jobObj);
            $name = $prop->getName();

            if ($name === 'media' && $val instanceof \Spatie\MediaLibrary\MediaCollections\Models\Media) {
                $mediaId = $val->id;
                $modelType = $val->model_type;
                $collectionName = $val->collection_name;
                break;
            }
            // Also try mediaId or similar
            if (in_array($name, ['mediaId', 'media_id']) && is_int($val)) {
                $mediaId = $val;
            }
        }
    } catch (\Throwable $e) {
        echo "  ERROR reflecting: " . $e->getMessage() . "\n";
    }

    if ($mediaId) {
        echo "  Media ID:    {$mediaId}\n";
        echo "  Model Type:  {$modelType}\n";
        echo "  Collection:  {$collectionName}\n";

        // Fetch from DB to get more info
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
        if ($media) {
            $path = $media->getPath();
            echo "  File:        {$media->file_name}\n";
            echo "  MIME:        {$media->mime_type}\n";
            echo "  Size:        {$media->human_readable_size}\n";
            echo "  Path:        {$path}\n";
            echo "  Exists:      " . (file_exists($path) ? 'YES' : 'NO') . "\n";
            echo "  Conversions: " . json_encode($media->generated_conversions ?? []) . "\n";
        } else {
            echo "  WARNING: Media record not found in DB (may have been deleted)\n";
        }
    } else {
        echo "  Could not extract media ID from job payload\n";
        // Try to find it from the exception message
        $exception = $job->exception ?? '';
        if (preg_match('/media[_\s]id[:\s=]+(\d+)/i', $exception, $m)) {
            echo "  Media ID from exception: " . $m[1] . "\n";
        }
    }

    // Show short exception summary
    $exc = $job->exception ?? '';
    $firstLine = strtok($exc, "\n");
    echo "  Exception:   {$firstLine}\n";
    echo "\n";
}
