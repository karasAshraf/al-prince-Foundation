@extends('layouts.app')

@section('title', $news->title_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.news.title'), 'url' => route('dashboard.news.index')],
        ['label' => $news->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $news->title_ar }}</h1>
        <a href="{{ route('dashboard.news.edit', $news) }}">
            <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">

            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
                <x-forms.image-preview :url="\App\Helpers\MediaHelper::url($news, 'news_images', 'image')" size="lg" />

                <h2 class="mt-4 text-lg font-semibold text-[#3D342A]">{{ $news->title_ar }}</h2>
                @if($news->title_en)
                    <p class="text-sm text-[#3D342A]/50">{{ $news->title_en }}</p>
                @endif

                <div class="prose prose-sm mt-4 max-w-none text-[#3D342A]">
                    {!! $news->content_ar !!}
                </div>

                @if($news->external_link_ar || $news->external_link_en || $news->external_link)
                    <div class="mt-4 border-t border-[#B49C6E]/20 pt-4 space-y-1 text-sm">
                        @if($news->external_link_ar)
                            <p><strong class="text-[#3D342A]">{{ __('dashboard.common.file') }} (AR):</strong> <a href="{{ $news->external_link_ar }}" target="_blank" class="text-[#A38B54] underline">{{ $news->external_link_ar }}</a></p>
                        @endif
                        @if($news->external_link_en)
                            <p><strong class="text-[#3D342A]">{{ __('dashboard.common.file') }} (EN):</strong> <a href="{{ $news->external_link_en }}" target="_blank" class="text-[#A38B54] underline">{{ $news->external_link_en }}</a></p>
                        @endif
                        @if($news->external_link && !$news->external_link_ar && !$news->external_link_en)
                            <p><strong class="text-[#3D342A]">{{ __('dashboard.common.file') }}:</strong> <a href="{{ $news->external_link }}" target="_blank" class="text-[#A38B54] underline">{{ $news->external_link }}</a></p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.common.status') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $news->status === 'published' ? __('dashboard.news.published') : __('dashboard.news.draft') }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.news.published_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $news->published_at?->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.common.updated_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $news->updated_at->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection