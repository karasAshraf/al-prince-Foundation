<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(\Illuminate\Http\Request::create('/', 'GET'));

echo "=== 1. DATABASE CHECK ===" . PHP_EOL;
$rawSettings = \Illuminate\Support\Facades\DB::table('settings')->get();
foreach ($rawSettings as $s) {
    echo "ID: {$s->id} | Group: {$s->group} | Key: {$s->key} | Value: {$s->value}" . PHP_EOL;
}

echo PHP_EOL . "=== 2. SETTING MODEL GROUP CHECK ===" . PHP_EOL;
$companyInfo = \App\Models\Setting::group('company_info');
var_dump($companyInfo);

echo PHP_EOL . "=== 3. VIEW COMPOSER / SHARE CHECK ===" . PHP_EOL;
$renderedView = view('layouts.frontend')->render();

echo PHP_EOL . "=== 4. CHECK IF DB DATA EXISTS IN RENDERED HTML ===" . PHP_EOL;
echo "Contains 'مؤسسة الأثر للتنمية': " . (str_contains($renderedView, 'مؤسسة الأثر للتنمية') ? "YES" : "NO") . PHP_EOL;
echo "Contains 'karasashrafdataexpert1@gmail.com': " . (str_contains($renderedView, 'karasashrafdataexpert1@gmail.com') ? "YES" : "NO") . PHP_EOL;
echo "Contains '01211440579': " . (str_contains($renderedView, '01211440579') ? "YES" : "NO") . PHP_EOL;
echo "Contains 'طريق الملك فهد': " . (str_contains($renderedView, 'طريق الملك فهد') ? "YES" : "NO") . PHP_EOL;
echo "Contains 'https://www.facebook.com/alatharfoundation': " . (str_contains($renderedView, 'https://www.facebook.com/alatharfoundation') ? "YES" : "NO") . PHP_EOL;
