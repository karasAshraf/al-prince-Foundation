<?php
/**
 * Fix-and-convert script:
 * 1. Re-save the PNG stripping the bad iCCP profile (GD roundtrip)
 * 2. Trigger Spatie's hero conversion synchronously
 */
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Suppress the iCCP warning so we can still load the image
// GD will still load the file despite the libpng warning if we suppress errors
set_error_handler(function($errno, $errstr) {
    // suppress iCCP libpng warnings
    return str_contains($errstr, 'iCCP') || str_contains($errstr, 'libpng');
});

config(['queue.default' => 'sync']);

$section = \App\Models\HomePageSection::where('type', 'hero_slider')->first();
if (!$section) { echo "No hero_slider\n"; exit(1); }

$media = $section->getMedia('home_section_images');
echo "Media count: " . $media->count() . "\n";

foreach ($media as $m) {
    $originalPath = $m->getPath();
    echo "Original path: {$originalPath}\n";
    echo "File exists: " . (file_exists($originalPath) ? 'YES' : 'NO') . "\n";

    if (!file_exists($originalPath)) {
        echo "ERROR: source file not found\n";
        continue;
    }

    // --- Step 1: Re-save PNG without the bad iCCP profile ---
    echo "Step 1: Stripping iCCP profile from PNG...\n";

    // Use error suppression to load despite bad profile
    $imgRes = @imagecreatefrompng($originalPath);
    if (!$imgRes) {
        echo "ERROR: GD could not load the PNG even with suppression\n";
        // Try a different approach: use chunk-level stripping
        $raw = file_get_contents($originalPath);
        // Remove iCCP chunk: find and remove it from PNG binary
        // PNG chunks: 4-byte length + 4-byte type + data + 4-byte CRC
        $cleanRaw = removePngChunk($raw, 'iCCP');
        $tempPath = $originalPath . '.clean.png';
        file_put_contents($tempPath, $cleanRaw);
        $imgRes = @imagecreatefrompng($tempPath);
        if (!$imgRes) {
            echo "ERROR: Still cannot load after iCCP removal\n";
            @unlink($tempPath);
            continue;
        }
        // Replace the original with clean version
        rename($tempPath, $originalPath);
        echo "iCCP chunk removed via binary method\n";
    } else {
        // Re-save to strip the embedded ICC profile
        $tmpPath = $originalPath . '.tmp.png';
        imagepng($imgRes, $tmpPath, 0); // 0 = no compression (fastest, lossless)
        imagedestroy($imgRes);
        rename($tmpPath, $originalPath);
        echo "PNG re-saved without iCCP profile\n";
    }

    restore_error_handler();

    // --- Step 2: Force the hero conversion synchronously ---
    echo "Step 2: Running hero conversion...\n";
    $fileManipulator = app(\Spatie\MediaLibrary\Conversions\FileManipulator::class);
    $conversions = \Spatie\MediaLibrary\Conversions\ConversionCollection::createForMedia($m);
    $heroConversions = $conversions->filter(fn($c) => $c->getName() === 'hero');
    
    if ($heroConversions->isEmpty()) {
        echo "ERROR: 'hero' conversion still not found on model\n";
        continue;
    }

    $fileManipulator->performConversions($heroConversions, $m);
    echo "Conversion job dispatched/performed\n";

    // Refresh and check DB
    $m->refresh();
    echo "Conversions: " . json_encode($m->generated_conversions) . "\n";

    // Check disk
    $convDir = dirname($originalPath) . DIRECTORY_SEPARATOR . 'conversions';
    if (is_dir($convDir)) {
        $files = array_values(array_diff(scandir($convDir), ['.', '..']));
        echo "Conv files on disk: " . json_encode($files) . "\n";
    } else {
        echo "Conv dir missing: {$convDir}\n";
    }
}

function removePngChunk(string $data, string $chunkType): string
{
    $pos = 8; // skip PNG signature
    $result = substr($data, 0, 8); // keep signature
    $len = strlen($data);
    while ($pos < $len) {
        $chunkLen = unpack('N', substr($data, $pos, 4))[1];
        $type = substr($data, $pos + 4, 4);
        $totalChunk = 4 + 4 + $chunkLen + 4; // len+type+data+crc
        if ($type !== $chunkType) {
            $result .= substr($data, $pos, $totalChunk);
        } else {
            echo "  Removed {$chunkType} chunk ({$chunkLen} bytes)\n";
        }
        $pos += $totalChunk;
    }
    return $result;
}
