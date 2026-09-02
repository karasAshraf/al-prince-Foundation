@extends('layouts.app')

@section('title', __('dashboard.surveys.responses') . ': ' . $survey->title)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.surveys.title'), 'url' => route('dashboard.surveys.index')],
        ['label' => $survey->title, 'url' => route('dashboard.surveys.show', $survey)],
        ['label' => __('dashboard.surveys.responses_results'), 'url' => null],
    ];
    $locale = app()->getLocale();
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ __('dashboard.surveys.responses_results') }}</h1>
            <p class="text-xs text-[#3D342A]/60 mt-0.5">{{ $survey->title }}</p>
        </div>
        <a href="{{ route('dashboard.surveys.show', $survey) }}">
            <x-buttons.secondary>{{ __('dashboard.surveys.back_to_survey') }}</x-buttons.secondary>
        </a>
    </div>

    @if($responses->isEmpty())
        <x-tables.empty-state
            title="{{ __('dashboard.surveys.no_responses') }}"
            message="{{ __('dashboard.common.empty_state') }}"
        />
    @else
        <div class="space-y-4">
            <x-tables.table :headers="['#', __('dashboard.surveys.respondent'), __('dashboard.surveys.submitted_answers'), __('dashboard.surveys.submission_date')]">
                @foreach($responses as $index => $res)
                    <x-tables.table-row>
                        <td class="px-4 py-3 text-sm font-bold text-[#3D342A]">
                            {{ $responses->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 text-sm text-[#3D342A] space-y-1">
                            @if($res->respondent_name)
                                <div class="font-bold text-[#3D342A]">{{ $res->respondent_name }}</div>
                            @endif
                            @if($res->respondent_phone)
                                <div class="text-xs font-mono text-[#3D342A]/80">{{ $res->respondent_phone }}</div>
                            @endif
                            @if($res->respondent_email)
                                <div class="text-xs text-[#3D342A]/60">{{ $res->respondent_email }}</div>
                            @endif
                            @if(!$res->respondent_name && !$res->respondent_phone && !$res->respondent_email)
                                <div>{{ __('dashboard.surveys.visitor') }} ({{ $res->ip_address ?: __('dashboard.surveys.anonymous') }})</div>
                            @elseif($res->ip_address)
                                <div class="text-[10px] text-[#3D342A]/40 mt-1">IP: {{ $res->ip_address }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="space-y-1.5 max-w-lg">
                                @if(is_array($res->answers))
                                    @foreach($res->answers as $qKey => $ans)
                                        @php
                                            $questionObj = is_numeric($qKey) && isset($survey->questions[$qKey]) ? $survey->questions[$qKey] : null;
                                            $questionLabel = $questionObj ? (is_array($questionObj) ? ($locale === 'ar' ? ($questionObj['label_ar'] ?? $questionObj['label_en'] ?? '') : ($questionObj['label_en'] ?? $questionObj['label_ar'] ?? '')) : $questionObj) : $qKey;
                                            
                                            $ansText = '';
                                            if (is_array($ans)) {
                                                $resolved = [];
                                                foreach ($ans as $subAns) {
                                                    if ($questionObj && isset($questionObj['options']) && is_numeric($subAns) && isset($questionObj['options'][$subAns])) {
                                                        $optObj = $questionObj['options'][$subAns];
                                                        $resolved[] = is_array($optObj) ? ($locale === 'ar' ? ($optObj['ar'] ?? $optObj['en'] ?? '') : ($optObj['en'] ?? $optObj['ar'] ?? '')) : $optObj;
                                                    } else {
                                                        $resolved[] = $subAns;
                                                    }
                                                }
                                                $ansText = implode(', ', $resolved);
                                            } else {
                                                if ($questionObj && isset($questionObj['options']) && is_numeric($ans) && isset($questionObj['options'][$ans])) {
                                                    $optObj = $questionObj['options'][$ans];
                                                    $ansText = is_array($optObj) ? ($locale === 'ar' ? ($optObj['ar'] ?? $optObj['en'] ?? '') : ($optObj['en'] ?? $optObj['ar'] ?? '')) : $optObj;
                                                } else {
                                                    $ansText = $ans;
                                                }
                                            }
                                        @endphp
                                        <div class="rounded bg-secondary border border-[#B49C6E]/20 p-2 text-xs">
                                            <span class="font-semibold text-[#A38B54]">{{ $questionLabel }}:</span>
                                            <span class="text-[#3D342A]">{{ $ansText }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-[#3D342A]/60">
                            {{ $res->created_at?->format('Y-m-d H:i') }}
                        </td>
                    </x-tables.table-row>
                @endforeach
            </x-tables.table>

            <x-tables.pagination :paginator="$responses" />
        </div>
    @endif

@endsection
