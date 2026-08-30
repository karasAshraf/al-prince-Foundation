{{--
    resources/views/components/application-logo.blade.php

    Renders the foundation logo image (Cloudinary URL or local storage path).

    KNOWN ISSUE THIS HANDLES: the settings.value column has, at different
    points, been JSON and LONGTEXT. If a value was saved via json_encode()
    without a matching json_decode() on read (or vice versa), a plain string
    value can come back wrapped in literal double-quote characters, e.g.
        "https://res.cloudinary.com/.../logo.png"
    instead of the clean value. That leading `"` breaks any startsWith/regex
    check for http/https. We defensively strip that here before checking.
--}}

@php
    $logoValue = null;

    try {
        $companyInfo = \App\Models\Setting::group('company_info');
        $logoValue = $companyInfo['logo'] ?? null;
    } catch (\Throwable $e) {
        $logoValue = null;
    }

    $logoUrl = null;

    if ($logoValue) {
        // 1. Trim whitespace/newlines that may have been introduced by
        //    copy-paste or storage round-tripping.
        $logoValue = trim($logoValue);

        // 2. Strip a single layer of literal surrounding quotes, in case the
        //    value is a JSON-encoded string that was never json_decode()'d
        //    (e.g. value stored as `"https://..."` including the quotes).
        if (\Illuminate\Support\Str::startsWith($logoValue, '"') && \Illuminate\Support\Str::endsWith($logoValue, '"')) {
            $decoded = json_decode($logoValue, true);
            if (is_string($decoded)) {
                $logoValue = $decoded;
            } else {
                $logoValue = trim($logoValue, '"');
            }
        }

        // 3. Now classify: external URL vs local storage path.
        $isExternalUrl = \Illuminate\Support\Str::startsWith(strtolower($logoValue), ['http://', 'https://', '//']);

        if ($isExternalUrl) {
            $logoUrl = $logoValue;
        } else {
            $cleanPath = preg_replace('#^/?storage/#', '', $logoValue);

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
                $logoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($cleanPath);
            }
        }
    }

    if (! $logoUrl) {
        $logoUrl = asset('images/logo-default.png');
    }

    // TEMPORARY DEBUG — uncomment the line below to inspect the raw value
    // reaching this component if the issue persists after this fix:
    // dd(['raw_setting_value' => $companyInfo['logo'] ?? 'NULL', 'resolved_logo_value' => $logoValue, 'final_url' => $logoUrl]);
@endphp

<img
    {{ $attributes->merge(['class' => 'block']) }}
    src="{{ $logoUrl }}"
    alt="{{ __('frontend.brand_name') }}"
    loading="lazy"
>
