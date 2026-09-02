@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تحليل نتائج الاستبيان' : 'Survey Results Analysis')

@php
    $breadcrumbs = [
        ['label' => __('dashboard.surveys.title'), 'url' => route('dashboard.surveys.index')],
        ['label' => app()->getLocale() === 'ar' ? 'تحليل النتائج' : 'Results Analysis', 'url' => null]
    ];
    $locale = app()->getLocale();
@endphp

@section('content')

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#B49C6E]/20 pb-4">
        <div>
            <span class="inline-block rounded-full bg-secondary/60 px-3 py-1 text-xs font-bold text-[#3D342A] mb-2">
                {{ $survey->type ?: '—' }}
            </span>
            <h1 class="text-2xl font-bold text-[#3D342A]">{{ $survey->title }}</h1>
            <p class="text-xs text-[#3D342A]/60 mt-1">{{ app()->getLocale() === 'ar' ? 'تحليل تفصيلي وإحصائيات تفاعلية للإجابات' : 'Detailed analysis and interactive charts of responses' }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.surveys.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-background px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-background transition-all">
                {{ app()->getLocale() === 'ar' ? 'عودة للاستبيانات' : 'Back to Surveys' }}
            </a>
            <a href="{{ route('dashboard.surveys.responses', $survey) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-[#A38B54]/10 px-4 py-2.5 text-xs font-bold text-[#A38B54] hover:bg-[#A38B54]/20 transition-all">
                {{ app()->getLocale() === 'ar' ? 'عرض الردود الفردية' : 'View Individual Responses' }}
            </a>
        </div>
    </div>

    {{-- ============ STAT CARDS ============ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-[#B49C6E]/20 bg-background p-5 shadow-sm">
            <p class="text-xs font-bold text-[#3D342A]/40 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'إجمالي الردود المستلمة' : 'Total Responses Received' }}</p>
            <p class="text-3xl font-extrabold text-[#A38B54] mt-2">{{ $survey->responses_count }}</p>
        </div>
        <div class="rounded-2xl border border-[#B49C6E]/20 bg-background p-5 shadow-sm">
            <p class="text-xs font-bold text-[#3D342A]/40 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'عدد الأسئلة' : 'Number of Questions' }}</p>
            <p class="text-3xl font-extrabold text-[#3D342A] mt-2">{{ count($questions) }}</p>
        </div>
        <div class="rounded-2xl border border-[#B49C6E]/20 bg-background p-5 shadow-sm">
            <p class="text-xs font-bold text-[#3D342A]/40 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'حالة الاستبيان' : 'Survey Status' }}</p>
            <div class="mt-3">
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $survey->is_active ? 'bg-secondary text-[#766868]' : 'bg-red-50 text-red-700' }}">
                    {{ $survey->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============ QUESTIONS ANALYSIS ============ --}}
    <div class="mt-8 space-y-6">
        <h2 class="text-lg font-bold text-[#3D342A]">{{ app()->getLocale() === 'ar' ? 'تحليل إجابات الأسئلة' : 'Question Answers Analysis' }}</h2>

        @forelse($questions as $index => $q)
            @php
                $qText = $locale === 'ar' ? ($q['label_ar'] ?? $q['label_en'] ?? $q['title_ar'] ?? $q['question'] ?? '') : ($q['label_en'] ?? $q['label_ar'] ?? $q['title_en'] ?? $q['question'] ?? '');
                $qType = $q['type'] ?? 'text';
            @endphp

            <div class="rounded-2xl border border-[#B49C6E]/20 bg-background p-6 shadow-sm overflow-hidden">
                <div class="flex items-start gap-3 mb-4">
                    <span class="flex items-center justify-center shrink-0 w-6 h-6 rounded-full bg-[#A38B54]/10 text-[#A38B54] text-xs font-bold">
                        {{ $index + 1 }}
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-[#3D342A]">{{ $qText }}</h3>
                        <span class="inline-block text-[10px] uppercase font-bold text-gray-400 mt-0.5">
                            {{ $qType }}
                        </span>
                    </div>
                </div>

                {{-- Rating type visualization --}}
                @if($qType === 'rating')
                    <div class="space-y-2 min-w-0">
                        <h4 class="text-xs font-bold text-[#3D342A]/60">{{ app()->getLocale() === 'ar' ? 'توزيع التقييمات:' : 'Rating Distribution:' }}</h4>
                        <div class="space-y-1 text-xs">
                            @foreach($chartsData[$index] ?? [] as $val => $count)
                                @php
                                    $percent = $survey->responses_count > 0 ? round(($count / $survey->responses_count) * 100) : 0;
                                @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <span class="w-12 font-bold text-[#3D342A]">⭐ {{ $val }}</span>
                                    <div class="flex-grow bg-background h-2.5 rounded-full overflow-hidden">
                                        <div class="bg-[#A38B54] h-full rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="w-16 text-end text-[#3D342A]/80 font-semibold">{{ $count }} ({{ $percent }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                {{-- Choice, Select, or Checkbox type visualization --}}
                @elseif(($qType === 'choice' || $qType === 'select' || $qType === 'checkbox') && isset($chartsData[$index]))
                    <div class="space-y-2 min-w-0">
                        <h4 class="text-xs font-bold text-[#3D342A]/60">{{ app()->getLocale() === 'ar' ? 'الخيارات المحددة:' : 'Selected Options:' }}</h4>
                        <div class="space-y-1 text-xs">
                            @foreach($chartsData[$index] ?? [] as $opt => $count)
                                @php
                                    $percent = $survey->responses_count > 0 ? round(($count / $survey->responses_count) * 100) : 0;
                                @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <span class="w-32 sm:w-48 truncate font-bold text-[#3D342A]" title="{{ $opt }}">{{ $opt }}</span>
                                    <div class="flex-grow bg-background h-2.5 rounded-full overflow-hidden">
                                        <div class="bg-[#B49C6E] h-full rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="w-16 text-end text-[#3D342A]/80 font-semibold">{{ $count }} ({{ $percent }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                {{-- Text answer or others list --}}
                @else
                    <div class="border-t border-background pt-3 space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                        @php
                            $textAnswers = [];
                            foreach($survey->responses as $resp) {
                                $ans = $resp->answers[$index] ?? null;
                                if(!empty($ans)) {
                                    $textAnswers[] = [
                                        'name' => $resp->respondent_name ?: ($locale === 'ar' ? 'مشارك مجهول' : 'Anonymous respondent'),
                                        'answer' => $ans
                                    ];
                                }
                            }
                        @endphp

                        @forelse($textAnswers as $ta)
                            <div class="bg-background dark:bg-gray-900/40 p-3 rounded-xl">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-bold text-[#A38B54]">{{ $ta['name'] }}</span>
                                </div>
                                <p class="text-xs text-[#3D342A] leading-relaxed">
                                    {{ is_array($ta['answer']) ? implode(', ', $ta['answer']) : $ta['answer'] }}
                                </p>
                            </div>
                        @empty
                            <p class="text-xs text-[#3D342A]/40 py-2 text-center">{{ app()->getLocale() === 'ar' ? 'لا توجد إجابات نصية مكتوبة بعد' : 'No text answers written yet' }}</p>
                        @endforelse
                    </div>
                @endif
            </div>
        @empty
            <p class="text-center text-sm text-[#3D342A]/50 py-4">{{ __('frontend.no_survey_questions') }}</p>
        @endforelse
    </div>

    {{-- ============ RESPONDENT CONTACT INFO ============ --}}
    <div class="mt-8 rounded-2xl border border-[#B49C6E]/20 bg-background p-6 shadow-sm">
        <h2 class="text-sm font-bold text-[#3D342A] mb-4 pb-2 border-b border-[#B49C6E]/10 flex items-center gap-2">
            {{ app()->getLocale() === 'ar' ? 'معلومات المشاركين وجهات الاتصال' : 'Participants & Contact Information' }}
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm text-[#3D342A]">
                <thead>
                    <tr class="border-b border-[#B49C6E]/10 text-xs font-bold text-[#3D342A]/60">
                        <th class="px-4 py-3 text-start">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                        <th class="px-4 py-3 text-start">{{ app()->getLocale() === 'ar' ? 'رقم الجوال' : 'Phone' }}</th>
                        <th class="px-4 py-3 text-start">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                        <th class="px-4 py-3 text-start">{{ app()->getLocale() === 'ar' ? 'تاريخ المشاركة' : 'Date Submitted' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#B49C6E]/10">
                    @forelse($responses as $resp)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $resp->respondent_name ?: '—' }}</td>
                            <td class="px-4 py-3 text-xs direction-ltr text-start">{{ $resp->respondent_phone ?: '—' }}</td>
                            <td class="px-4 py-3 text-xs">{{ $resp->respondent_email ?: '—' }}</td>
                            <td class="px-4 py-3 text-xs text-[#3D342A]/60">{{ $resp->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-xs text-[#3D342A]/40">{{ app()->getLocale() === 'ar' ? 'لا يوجد مشاركات بعد' : 'No responses yet' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $responses->links() }}
        </div>
    </div>

@endsection