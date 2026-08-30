@props([
    'name'     => null,
    'icon'     => null,
    'class'    => 'w-6 h-6',
    'fallback' => 'sparkles',
])

@php
    // Resolve the icon name from either prop
    $iconName = trim((string) ($name ?? $icon ?? ''));

    // If empty, use the fallback
    if ($iconName === '') {
        $iconName = $fallback ?: 'sparkles';
    }

    // Lucide icon names use kebab-case; normalise underscores
    $iconName = str_replace('_', '-', $iconName);
@endphp

{{--
    Render using Lucide's data-lucide attribute.
    lucide.createIcons() (loaded via CDN in the layout) will
    replace this <i> with an inline <svg> after DOM ready.
    The class is preserved by Lucide on the generated <svg>.
--}}
<i
    data-lucide="{{ $iconName }}"
    {{ $attributes->merge(['class' => $class]) }}
    aria-hidden="true"
></i>
