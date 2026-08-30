@php
    $logoUrl = null;

    // 1. Check setting from DB if available
    try {
        $dbLogo = $companyInfo['logo'] ?? null;
        if ($dbLogo) {
            if (preg_match('/^(https?:)?\/\//i', $dbLogo)) {
                $logoUrl = $dbLogo;
            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($dbLogo)) {
                $logoUrl = \Illuminate\Support\Facades\Storage::url($dbLogo);
            }
        }
    } catch (\Throwable $e) {
        // ignore DB exception if table not populated
    }

    // 2. Dynamic scan of public storage logo directory
    if (!$logoUrl) {
        try {
            $logoFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('logo');
            if (!empty($logoFiles)) {
                $logoUrl = \Illuminate\Support\Facades\Storage::url($logoFiles[0]);
            }
        } catch (\Throwable $e) {
            // ignore storage exception
        }
    }

    // 3. Fallback scan of any image in public storage root
    if (!$logoUrl) {
        try {
            $allPublicFiles = \Illuminate\Support\Facades\Storage::disk('public')->files();
            foreach ($allPublicFiles as $file) {
                if (preg_match('/\.(png|jpe?g|svg|webp)$/i', $file)) {
                    $logoUrl = \Illuminate\Support\Facades\Storage::url($file);
                    break;
                }
            }
        } catch (\Throwable $e) {
            // ignore storage exception
        }
    }

    // 4. Hardcoded relative fallback if logo file exists directly in public/storage/logo/
    if (!$logoUrl && file_exists(public_path('storage/logo/لوجو-02.png'))) {
        $logoUrl = asset('storage/logo/لوجو-02.png');
    }
@endphp

@if ($logoUrl)
    <img
        src="{{ $logoUrl }}"
        alt="{{ config('app.name', __('dashboard.common.foundation_name')) }}"
        {{ $attributes->merge(['class' => 'w-auto object-contain']) }}
    />
@else
    <span
        {{ $attributes->merge([
            'class' => 'flex items-center justify-center rounded-lg bg-[#A38B54] text-sm font-bold text-[#EAEAE9]'
        ]) }}
    >
        أث
    </span>
@endif
