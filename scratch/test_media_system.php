<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Helpers\MediaHelper;
use App\Models\HomePageSection;
use App\Models\Project;
use App\Models\News;
use App\Models\Service;
use App\Models\Program;

echo "=== MEDIA SYSTEM VERIFICATION ===\n\n";

// 1. Test MediaHelper::resolveUrl with various paths
$testPaths = [
    'http://example.com/image.jpg' => 'External http URL',
    'https://example.com/image.jpg' => 'External https URL',
    'storage/39/file.png' => 'Storage prefixed relative path',
    '/storage/39/file.png' => 'Leading slash storage prefixed relative path',
    '39/file.png' => 'Bare storage relative path',
    null => 'Null path',
    '' => 'Empty path',
];

echo "--- MediaHelper::resolveUrl Tests ---\n";
foreach ($testPaths as $input => $label) {
    $resolved = MediaHelper::resolveUrl($input);
    echo sprintf("[%s] '%s' => '%s'\n", $label, (string)$input, (string)$resolved);
}

echo "\n--- MediaHelper::isExternal Tests ---\n";
$testUrls = [
    'http://127.0.0.1:8000/storage/39/file.png',
    'https://external-domain.com/photo.jpg',
    '/storage/39/file.png',
    '39/file.png',
];

foreach ($testUrls as $url) {
    $isExt = MediaHelper::isExternal($url);
    echo sprintf("URL: '%s' => External: %s\n", $url, $isExt ? 'YES' : 'NO');
}

echo "\n--- Model Media Resolution Tests ---\n";

$homeSection = HomePageSection::first();
if ($homeSection) {
    $url = MediaHelper::url($homeSection, 'home_section_images', 'image');
    echo sprintf("HomePageSection ID %d URL: '%s'\n", $homeSection->id, (string)$url);
} else {
    echo "No HomePageSection found in DB.\n";
}

$project = Project::first();
if ($project) {
    $url = MediaHelper::url($project, 'project_images', 'image');
    echo sprintf("Project ID %d URL: '%s'\n", $project->id, (string)$url);
} else {
    echo "No Project found in DB.\n";
}

$news = News::first();
if ($news) {
    $url = MediaHelper::url($news, 'news_images', 'image');
    echo sprintf("News ID %d URL: '%s'\n", $news->id, (string)$url);
} else {
    echo "No News found in DB.\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
