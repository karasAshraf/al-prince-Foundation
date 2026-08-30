<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$section = \App\Models\HomePageSection::where('type', 'hero_slider')->first();
if (!$section) {
    echo "No hero_slider section found.\n";
    exit;
}

$media = $section->getMedia('home_section_images');
echo "Total hero images: " . $media->count() . "\n\n";
foreach ($media as $m) {
    echo "File:      " . $m->file_name . "\n";
    echo "Size:      " . $m->human_readable_size . "\n";
    echo "MIME:      " . $m->mime_type . "\n";
    echo "Disk:      " . $m->disk . "\n";
    echo "Conversions: " . json_encode(array_keys($m->generated_conversions ?? [])) . "\n";
    echo "---\n";
}
