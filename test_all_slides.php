<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$slides = \App\Models\HeroSlide::all();
foreach ($slides as $slide) {
    $url = \App\Helpers\MediaHelper::url($slide, 'hero_slide_images', 'image', 'thumb');
    $media = $slide->getFirstMedia('hero_slide_images');
    
    echo "Slide {$slide->id} ({$slide->title_ar}):\n";
    echo "  Media ID: " . ($media ? $media->id : 'NONE') . "\n";
    echo "  URL: {$url}\n";
    
    // check if it's broken
    if ($url) {
        $path = parse_url($url, PHP_URL_PATH); // e.g. /storage/611/...
        if (str_starts_with($path, '/storage/')) {
            $localPath = public_path($path);
            echo "  Exists on disk: " . (file_exists($localPath) ? 'YES' : 'NO') . "\n";
        } else {
            echo "  Not a local storage path.\n";
        }
    }
}
