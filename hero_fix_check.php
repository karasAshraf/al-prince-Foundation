<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$section = \App\Models\HomePageSection::where('type', 'hero_slider')->first();
if (!$section) { echo "No hero_slider\n"; exit(1); }

echo "Section ID: " . $section->id . "\n";
$media = $section->getMedia('home_section_images');
echo "Media count: " . $media->count() . "\n\n";

foreach ($media as $m) {
    echo "Media ID:    {$m->id}\n";
    echo "File:        {$m->file_name}\n";
    echo "Size:        {$m->human_readable_size}\n";
    echo "Path:        " . $m->getPath() . "\n";
    echo "Conversions: " . json_encode($m->generated_conversions ?? []) . "\n";
    $convDir = dirname($m->getPath()) . DIRECTORY_SEPARATOR . 'conversions';
    if (is_dir($convDir)) {
        $files = array_values(array_diff(scandir($convDir), ['.', '..']));
        echo "Conv files:  " . json_encode($files) . "\n";
    } else {
        echo "Conv dir:    MISSING (" . $convDir . ")\n";
    }
    echo "\n";
}

echo "image_driver: " . config('media-library.image_driver', '(not set)') . "\n";
echo "queue_by_default: " . var_export(config('media-library.queue_conversions_by_default'), true) . "\n";
echo "GD WebP: " . (function_exists('imagewebp') ? 'YES' : 'NO') . "\n";
