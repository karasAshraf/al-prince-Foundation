<?php

$pages = [
    'http://127.0.0.1:8000/surveys',
    // We cannot easily test /dashboard/services without auth, but we can verify it renders.
];

foreach ($pages as $url) {
    $html = file_get_contents($url);
    if ($html === false) {
        echo "Failed to load $url\n";
        continue;
    }
    echo "Loaded $url (Length: " . strlen($html) . ")\n";
    // Look for survey 1 image
    if (strpos($html, '532') !== false) {
        echo "PASS: Survey 1 image found in HTML\n";
    } else {
        echo "FAIL: Survey 1 image NOT found\n";
    }
}
