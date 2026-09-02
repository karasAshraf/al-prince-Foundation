<?php
$url = 'http://127.0.0.1:8000/storage/611/58c8ee9d-a39f-43c0-a570-9b738a50e62b.jpeg';
$ext = strtolower(pathinfo(strtok($url, '?'), PATHINFO_EXTENSION));
var_dump($ext);
$mediaCategory = 'unknown';
if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'])) {
    $mediaCategory = 'image';
}
var_dump($mediaCategory);
