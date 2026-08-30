<?php

$ar = require __DIR__ . '/../lang/ar/dashboard.php';

function array_flatten_keys_map($array, $prefix = '') {
    $result = [];
    foreach ($array as $key => $value) {
        $new_key = $prefix === '' ? $key : $prefix . '.' . $key;
        if (is_array($value)) {
            $result = array_merge($result, array_flatten_keys_map($value, $new_key));
        } else {
            $result[$new_key] = $value;
        }
    }
    return $result;
}

$dictionary = array_flatten_keys_map($ar);

$viewsDir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

$missingKeys = [];
$totalKeysChecked = 0;

foreach ($files as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        preg_match_all("/__\(['\"]dashboard\.([^'\"]+)['\"]\)/", $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key) {
                $totalKeysChecked++;
                if (!array_key_exists($key, $dictionary)) {
                    $missingKeys[$key][] = str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', $file->getPathname());
                }
            }
        }
    }
}

echo "Total dashboard key references checked across all Blade templates: {$totalKeysChecked}\n";

if (empty($missingKeys)) {
    echo "SUCCESS: All translation keys referenced in Blade views exist in the language dictionary!\n";
} else {
    echo "MISSING KEYS FOUND IN VIEWS:\n";
    foreach ($missingKeys as $key => $filesList) {
        echo " - dashboard.{$key} in:\n   * " . implode("\n   * ", array_unique($filesList)) . "\n";
    }
}
