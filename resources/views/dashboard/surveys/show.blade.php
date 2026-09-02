@extends('layouts.app')

@section('title', $survey->title)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.surveys.title'), 'url' => route('dashboard.surveys.index')],
        ['label' => $survey->title, 'url' => null],
    ];
    $locale = app()->getLocale();
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $survey->title }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.surveys.responses', $survey) }}">
                <x-buttons.secondary>{{ __('dashboard.surveys.responses') }} ({{ $survey->responses_count ?? $survey->responses()->count() }})</x-buttons.secondary>
            </a>
            <a href="{{ route('dashboard.surveys.edit', $survey) }}">
                <x-buttons.primary>{{ __('dashboard.surveys.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.surveys.index') }}">
                <x-buttons.secondary>{{ __('dashboard.surveys.back_to_list') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-6 shadow-sm space-y-4">
                <h2 class="text-lg font-bold text-[#3D342A]">{{ $survey->title_ar }}</h2>
                @if($survey->title_en)
                    <p class="text-sm text-[#3D342A]/60">{{ $survey->title_en }}</p>
                @endif

                @if($survey->description_ar || $survey->description_en)
                    <div class="border-t border-[#B49C6E]/20 pt-4 space-y-2">
                        @if($survey->description_ar)
                            <p class="text-sm text-[#3D342A] leading-relaxed">{{ $survey->description_ar }}</p>
                        @endif
                        @if($survey->description_en)
                            <p class="text-xs text-[#3D342A]/60 leading-relaxed">{{ $survey->description_en }}</p>
                        @endif
                    </div>
                @endif

                <div class="border-t border-[#B49C6E]/20 pt-4 space-y-3">
                    <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.surveys.questions') }}:</h3>
                    @if(is_array($survey->questions))
                        <div class="space-y-3">
                            @foreach($survey->questions as $index => $q)
                                @php
                                    $qLabel = is_array($q) ? ($locale === 'ar' ? ($q['label_ar'] ?? $q['label_en'] ?? '') : ($q['label_en'] ?? $q['label_ar'] ?? '')) : $q;
                                @endphp
                                <div class="rounded-lg border border-[#B49C6E]/30 bg-secondary/10 p-3.5 text-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-[#3D342A]">{{ $index + 1 }}. {{ $qLabel ?: __('dashboard.surveys.untitled') }}</span>
                                        <span class="rounded bg-[#A38B54]/10 px-2 py-0.5 text-xs text-[#A38B54] font-semibold">
                                            {{ $q['type'] ?? 'text' }}
                                        </span>
                                    </div>
                                    @if(isset($q['options']) && is_array($q['options']) && count($q['options']))
                                        <div class="mt-2 text-xs text-[#3D342A]/70 pr-4 space-y-0.5">
                                            @foreach($q['options'] as $opt)
                                                @php
                                                    $optLabel = is_array($opt) ? ($locale === 'ar' ? ($opt['ar'] ?? $opt['en'] ?? '') : ($opt['en'] ?? $opt['ar'] ?? '')) : $opt;
                                                @endphp
                                                <div>• {{ $optLabel }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-2">{{ __('dashboard.surveys.survey_data') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.surveys.type') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $survey->type ?: __('dashboard.surveys.general') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.status') }}</dt>
                        <dd class="font-medium {{ $survey->is_active ? 'text-[#A38B54]' : 'text-red-600' }}">
                            {{ $survey->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.surveys.starts_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $survey->starts_at?->format('Y-m-d') ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.surveys.ends_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $survey->ends_at?->format('Y-m-d') ?: '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection
