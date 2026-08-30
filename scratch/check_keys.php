<?php

$ar = require __DIR__ . '/../lang/ar/dashboard.php';
$en = require __DIR__ . '/../lang/en/dashboard.php';

function array_flatten_keys($array, $prefix = '') {
    $result = [];
    foreach ($array as $key => $value) {
        $new_key = $prefix === '' ? $key : $prefix . '.' . $key;
        if (is_array($value)) {
            $result = array_merge($result, array_flatten_keys($value, $new_key));
        } else {
            $result[] = $new_key;
        }
    }
    return $result;
}

$arKeys = array_flatten_keys($ar);
$enKeys = array_flatten_keys($en);

$missingInEn = array_diff($arKeys, $enKeys);
$missingInAr = array_diff($enKeys, $arKeys);

echo "AR total keys: " . count($arKeys) . "\n";
echo "EN total keys: " . count($enKeys) . "\n";

if (empty($missingInEn) && empty($missingInAr)) {
    echo "SUCCESS: 100% key parity between Arabic and English translation files!\n";
} else {
    echo "MISMATCH FOUND:\n";
    if (!empty($missingInEn)) {
        echo "Missing in EN:\n";
        print_r($missingInEn);
    }
    if (!empty($missingInAr)) {
        echo "Missing in AR:\n";
        print_r($missingInAr);
    }
}
