<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$media = \App\Models\HeroSlide::find(19)->getFirstMedia('hero_slide_images');
if ($media) {
    echo "Has generated conversion 'thumb': " . ($media->hasGeneratedConversion('thumb') ? 'YES' : 'NO') . "\n";
    echo "URL for 'thumb': " . $media->getUrl('thumb') . "\n";
    echo "URL original: " . $media->getUrl() . "\n";
    $path = $media->getPath('thumb');
    echo "Path 'thumb' exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";
} else {
    echo "No media found.\n";
}
