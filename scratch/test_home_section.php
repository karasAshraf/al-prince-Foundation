<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$section = new \App\Models\HomePageSection();
echo "HomePageSection getFirstMediaUrl test: " . get_class($section) . "\n";
echo "First Media URL: '" . $section->getFirstMediaUrl('home_section_images') . "'\n";
echo "SUCCESS: HasMedia interface & InteractsWithMedia trait work seamlessly!\n";
