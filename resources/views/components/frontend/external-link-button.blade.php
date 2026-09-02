@props([
    'model'      => null,
    'link'       => null,
    'collection' => 'default',
    'column'     => 'image',
    'label'      => null,
    'class'      => '',
])

@php
    $targetLink = $link ?? ($model ? ($model->external_link ?? $model->extra_link ?? $model->media_external_link ?? null) : null);
    $shouldShow = \App\Helpers\MediaHelper::shouldShowExternalLink($model, $targetLink, $collection, $column);
    $btnLabel   = $label ?? (app()->getLocale() === 'ar' ? 'زيارة الموقع الإلكتروني' : 'Visit Website');
@endphp

@if ($shouldShow && $targetLink)
    <div {{ $attributes->merge(['class' => 'pt-6 border-t border-secondary/20']) }}>
        <a href="{{ $targetLink }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2.5 px-6 py-3 text-sm font-bold rounded-xl bg-primary text-background hover:bg-[#8A734A] hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 {{ $class }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.5 3a17 17 0 000 18M12.5 3a17 17 0 010 18" />
            </svg>
            <span>{{ $btnLabel }}</span>
            <svg class="w-4 h-4 shrink-0 opacity-80 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    </div>
@endif
