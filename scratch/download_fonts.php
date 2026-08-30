<?php

$destDir = __DIR__ . '/../public/fonts/ibm-plex-sans-arabic';
if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

$urls = [
    'ibm-plex-sans-arabic-arabic-600-normal.woff2' => 'https://cdn.jsdelivr.net/npm/@fontsource/ibm-plex-sans-arabic@latest/files/ibm-plex-sans-arabic-arabic-600-normal.woff2',
    'ibm-plex-sans-arabic-latin-600-normal.woff2' => 'https://cdn.jsdelivr.net/npm/@fontsource/ibm-plex-sans-arabic@latest/files/ibm-plex-sans-arabic-latin-600-normal.woff2',
    'ibm-plex-sans-arabic-latin-ext-600-normal.woff2' => 'https://cdn.jsdelivr.net/npm/@fontsource/ibm-plex-sans-arabic@latest/files/ibm-plex-sans-arabic-latin-ext-600-normal.woff2'
];

foreach ($urls as $filename => $url) {
    echo "Downloading {$url}...\n";
    $content = file_get_contents($url);
    if ($content === false) {
        echo "Failed to download {$filename}\n";
        exit(1);
    }
    $destFile = $destDir . '/' . $filename;
    if (file_put_contents($destFile, $content) === false) {
        echo "Failed to write {$filename} to {$destFile}\n";
        exit(1);
    }
    echo "Successfully saved {$filename}\n";
}

echo "All downloads completed!\n";
