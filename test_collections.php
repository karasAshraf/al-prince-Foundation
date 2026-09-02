<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$medias = \DB::table('media')->where('model_type', 'App\Models\HeroSlide')->get();
foreach ($medias as $m) {
    echo "ID: {$m->model_id} | Collection: {$m->collection_name} | File: {$m->file_name}\n";
}
