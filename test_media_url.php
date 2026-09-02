<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$slide = \App\Models\HeroSlide::find(19);
$url = \App\Helpers\MediaHelper::url($slide, 'hero_slide_images', 'image', 'thumb');
echo "MediaHelper URL: " . $url . "\n";
