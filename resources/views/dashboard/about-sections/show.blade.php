@extends('layouts.app')

@section('title', $item->title_ar ?? __('dashboard.about_sections.show'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.about_sections.title'), 'url' => route('dashboard.about-sections.index')],
        ['label' => $item->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $item->title_ar }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.about-sections.edit', $item) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.about-sections.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-sm space-y-4">
                <x-forms.image-preview :url="\App\Helpers\MediaHelper::url($item, 'about_images', 'image')" size="lg" />

                <h2 class="text-xl font-bold text-[#3D342A]">{{ $item->title_ar }}</h2>
                @if($item->title_en)
                    <p class="text-sm text-[#3D342A]/60">{{ $item->title_en }}</p>
                @endif

                <div class="prose prose-sm max-w-none text-[#3D342A] border-t border-[#B49C6E]/20 pt-4">
                    {!! $item->description_ar !!}
                </div>

                @if($item->description_en)
                    <div class="prose prose-sm max-w-none text-[#3D342A]/70 border-t border-[#B49C6E]/20 pt-4">
                        <h4 class="text-xs font-semibold text-[#3D342A]">English Content:</h4>
                        {!! $item->description_en !!}
                    </div>
                @endif

                @if($item->video)
                    <div class="border-t border-[#B49C6E]/20 pt-4">
                        <p class="text-xs font-semibold text-[#3D342A]">{{ __('dashboard.about_sections.video') }}: <a href="{{ $item->video }}" target="_blank" class="text-[#A38B54] underline">{{ $item->video }}</a></p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-2">{{ __('dashboard.common.details') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.status') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->status === 'published' ? __('dashboard.common.published') : __('dashboard.common.draft') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.updated_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->updated_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection
