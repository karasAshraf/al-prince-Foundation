@extends('layouts.app')

@php
    use App\Helpers\MediaHelper;
    $item = $section;
    $breadcrumbs = [
        ['label' => __('dashboard.home_sections.title'), 'url' => route('dashboard.home-sections.index')],
        ['label' => $item->title_ar, 'url' => null],
    ];
    $coverUrl = MediaHelper::url($item, 'home_section_images', 'image');
    // For home sections, the external/CTA link lives in extra_link
    if (!$coverUrl && !empty($item->extra_link)) {
        $coverUrl = $item->extra_link;
    }
@endphp

@section('title', $item->title_ar ?? __('dashboard.home_sections.show'))

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $item->title_ar }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.home-sections.edit', $item) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
            <a href="{{ route('dashboard.home-sections.index') }}">
                <x-buttons.secondary>← {{ __('dashboard.common.back') }}</x-buttons.secondary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-6 shadow-sm space-y-4">
                @if($coverUrl)
                    <div class="overflow-hidden rounded-xl border border-[#B49C6E]/20">
                        <x-forms.image-preview :url="$coverUrl" size="lg" />
                    </div>
                @endif

                @if($item->label_ar || $item->label_en)
                    <div class="flex flex-wrap gap-2">
                        @if($item->label_ar)
                            <span class="inline-block rounded-md bg-[#A38B54]/10 px-3 py-1 text-xs font-semibold text-[#A38B54]" title="العنوان الفرعي (AR)">
                                {{ $item->label_ar }}
                            </span>
                        @endif
                        @if($item->label_en)
                            <span class="inline-block rounded-md bg-[#EAEAE9]/50 px-3 py-1 text-xs font-semibold text-[#A38B54]" title="العنوان الفرعي (EN)">
                                {{ $item->label_en }}
                            </span>
                        @endif
                    </div>
                @endif

                <h2 class="text-xl font-bold text-[#3D342A]">{{ $item->title_ar }}</h2>
                @if($item->title_en)
                    <p class="text-sm text-[#3D342A]/60">{{ $item->title_en }}</p>
                @endif

                @if($item->description_ar)
                    <div class="border-t border-[#B49C6E]/20 pt-4">
                        <h3 class="text-xs font-semibold text-[#3D342A]/70 mb-1">{{ __('dashboard.common.details') }} (AR)</h3>
                        <p class="text-sm text-[#3D342A] leading-relaxed">{{ $item->description_ar }}</p>
                    </div>
                @endif

                @if($item->description_en)
                    <div class="border-t border-[#B49C6E]/20 pt-4">
                        <h3 class="text-xs font-semibold text-[#3D342A]/70 mb-1">{{ __('dashboard.common.details') }} (EN)</h3>
                        <p class="text-sm text-[#3D342A] leading-relaxed">{{ $item->description_en }}</p>
                    </div>
                @endif

                @if($item->extra_link)
                    <div class="border-t border-[#B49C6E]/20 pt-4">
                        <p class="text-xs text-[#3D342A]/60">{{ __('dashboard.common.file') }}: <a href="{{ $item->extra_link }}" target="_blank" class="text-[#A38B54] underline">{{ $item->extra_link }}</a></p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-2">{{ __('dashboard.common.details') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        @php
                            $typeLabels = [
                                'slider' => 'سلايدر (Slider)',
                                'about_preview' => 'قسم نبذة عنا (About Preview)',
                                'service_section' => 'قسم الخدمات (Services Preview)',
                                'projects_preview' => 'قسم المشاريع (Projects Preview)',
                                'counters' => 'قسم العدادات (Counters)',
                                'latest_news' => 'قسم أحدث الأخبار (Latest News)',
                                'cta' => 'قسم اتخاذ إجراء (CTA)',
                                'home_section' => 'قسم رئيسي عام (Home Section)',
                            ];
                        @endphp
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.type') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $typeLabels[$item->type] ?? $item->type }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.status') }}</dt>
                        <dd class="font-medium text-[#A38B54]">{{ $item->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#3D342A]/60">{{ __('dashboard.common.order') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $item->order }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

@endsection
