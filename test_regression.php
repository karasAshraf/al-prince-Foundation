<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$serviceService = app(ServiceService::class);

echo "--- 1. UPLOAD NEW IMAGE ---\n";
// Create a fake uploaded file
$tempPath = storage_path('app/temp_test_image.jpg');
file_put_contents($tempPath, 'fake-image-content');
$file = new UploadedFile($tempPath, 'test_image.jpg', 'image/jpeg', null, true);

$data = [
    'title_ar' => 'Test Service Upload',
    'title_en' => 'Test Service Upload EN',
    'description_ar' => 'Test Description',
    'is_active' => true,
    'image' => $file
];

$service = $serviceService->create($data);
$media = $service->getFirstMedia('service_images');

echo "Service ID: {$service->id}\n";
echo "Media ID: " . ($media ? $media->id : 'NONE') . "\n";
echo "File exists on disk: " . (file_exists($media->getPath()) ? 'YES' : 'NO') . "\n";
$url = \App\Helpers\MediaHelper::url($service, 'service_images');
echo "Media URL: {$url}\n";

// Test HTTP
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP Status: {$code}\n\n";

echo "--- 2. UPDATE WITHOUT NEW IMAGE ---\n";
$dataUpdate = [
    'title_ar' => 'Test Service Upload (Updated)',
];
$service = $serviceService->update($service, $dataUpdate);
$media2 = $service->getFirstMedia('service_images');
echo "Media ID still the same? " . ($media2 && $media2->id === $media->id ? 'YES' : 'NO') . "\n";
echo "Media URL: " . \App\Helpers\MediaHelper::url($service, 'service_images') . "\n\n";

echo "--- 3. REPLACE IMAGE ---\n";
$tempPath2 = storage_path('app/temp_test_image2.png');
file_put_contents($tempPath2, 'fake-image-content-2');
$file2 = new UploadedFile($tempPath2, 'test_image2.png', 'image/png', null, true);

$dataReplace = [
    'title_ar' => 'Test Service Upload (Replaced Image)',
    'image' => $file2
];
$service = $serviceService->update($service, $dataReplace);
$media3 = $service->getFirstMedia('service_images');
echo "Media ID replaced? " . ($media3 && $media3->id !== $media2->id ? 'YES' : 'NO') . "\n";
echo "New Media ID: {$media3->id}\n";
$url3 = \App\Helpers\MediaHelper::url($service, 'service_images');
echo "New Media URL: {$url3}\n";

$ch = curl_init($url3);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$code3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "New HTTP Status: {$code3}\n";
