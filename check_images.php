<?php
foreach(\App\Models\AboutSection::all() as $s) {
    echo $s->slug . ": \n";
    echo "  detail: " . \App\Helpers\MediaHelper::url($s, 'about_images', 'image', 'detail') . "\n";
    echo "  thumb: " . \App\Helpers\MediaHelper::url($s, 'about_images', 'image', 'thumb') . "\n";
}
