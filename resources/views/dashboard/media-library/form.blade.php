@extends('layouts.app')

@section('title', $item->id ? __('dashboard.media_library.edit') : __('dashboard.media_library.create'))

@php
    $breadcrumbs = [
        ['label' => __('dashboard.media_library.title'), 'url' => route('dashboard.media-library.index')],
        ['label' => $item->id ? __('dashboard.media_library.edit') : __('dashboard.media_library.create'), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $item->id ? __('dashboard.media_library.edit') : __('dashboard.media_library.create') }}</h1>
        <a href="{{ route('dashboard.media-library.index') }}">
            <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
        </a>
    </div>

    <x-alerts.validation />

    <form method="POST" 
          action="{{ $item->id ? route('dashboard.media-library.update', $item) : route('dashboard.media-library.store') }}" 
          enctype="multipart/form-data">
        @csrf
        @if($item->id)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main Fields Column --}}
            <div class="space-y-5 lg:col-span-2">
                <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-forms.input
                            name="title_ar"
                            label="{{ __('dashboard.media_library.title_ar') }}"
                            :value="old('title_ar', $item->title_ar ?? '')"
                            required
                        />
                        <x-forms.input
                            name="title_en"
                            label="{{ __('dashboard.media_library.title_en') }}"
                            :value="old('title_en', $item->title_en ?? '')"
                        />
                    </div>

                    <div class="mt-4">
                        <x-forms.slug-input
                            name="slug"
                            :value="old('slug', $item->slug ?? '')"
                            source-field="title_ar"
                        />
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-forms.textarea
                            name="description_ar"
                            label="{{ __('dashboard.media_library.desc_ar') }}"
                            :value="old('description_ar', $item->description_ar ?? '')"
                            rows="4"
                        />
                        <x-forms.textarea
                            name="description_en"
                            label="{{ __('dashboard.media_library.desc_en') }}"
                            :value="old('description_en', $item->description_en ?? '')"
                            rows="4"
                        />
                    </div>
                </div>

                {{-- SEO Fields --}}
                <x-forms.seo-fields :seo-meta="$item->seoMeta ?? null" />
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-5">
                <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-[#3D342A]">{{ __('dashboard.common.details') }}</h3>

                    <x-forms.select
                        name="category"
                        label="{{ __('dashboard.media_library.category') }}"
                        :options="$categories"
                        :selected="old('category', $item->category ?? '')"
                    />

                    <x-forms.input
                        name="order"
                        label="{{ __('dashboard.common.order') }}"
                        type="number"
                        :value="old('order', $item->order ?? 0)"
                    />

                    <x-forms.toggle
                        name="is_active"
                        label="{{ __('dashboard.common.active') }}"
                        :checked="old('is_active', $item->is_active ?? true)"
                    />
                </div>

                {{-- Document Upload --}}
                <x-forms.media-upload
                    name="file"
                    url-name="external_link"
                    label="{{ __('dashboard.media_library.file_or_link') }}"
                    :allow-video="true"
                    :allow-pdf="true"
                    :allow-external="true"
                    :multiple="true"
                    :media-items="$item->id ? $item->getMedia('media_library_files') : null"
                    :external-links="$item->external_link"
                />
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
            <a href="{{ route('dashboard.media-library.index') }}">
                <x-buttons.secondary type="button">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>
    </form>
@endsection
