@extends('layouts.app')

@section('title', __('dashboard.dashboard.home'))

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-[18px] font-medium text-[#3D342A]">{{ __('dashboard.dashboard.overview') }}</h1>
        <a href="{{ route('dashboard.analytics') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#A38B54]/10 px-4 py-2 text-xs font-bold text-[#A38B54] hover:bg-[#A38B54]/20 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2zm0 0v-7a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z" />
            </svg>
            {{ app()->getLocale() === 'ar' ? 'عرض التحليلات التفصيلية' : 'View Detailed Analytics' }}
        </a>
    </div>

    {{-- ============ STAT CARDS — 4-column grid, 12px gap ============ --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <x-cards.stat-card
            label="{{ __('dashboard.dashboard.news_count') }}"
            :value="$newsCount"
            icon="newspaper"
            :url="route('dashboard.news.index')"
        />
        <x-cards.stat-card
            label="{{ __('dashboard.dashboard.programs_count') }}"
            :value="$programsCount"
            icon="folder"
            :url="route('dashboard.programs.index')"
        />
        <x-cards.stat-card
            label="{{ __('dashboard.dashboard.projects_count') }}"
            :value="$projectsCount"
            icon="flag"
            :url="route('dashboard.projects.index')"
        />
        <x-cards.stat-card
            label="{{ __('dashboard.dashboard.users_count') }}"
            :value="$usersCount"
            icon="users"
            :url="route('dashboard.users.index')"
        />
    </div>

    {{-- ============ RECENT ACTIVITY — two-column panels ============ --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- Latest Surveys --}}
        <div class="rounded-xl border border-[#B7B5B3]/60 bg-white px-4 py-3.5">
            {{-- Panel header --}}
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-[14px] font-medium text-[#3D342A]">{{ __('dashboard.dashboard.latest_surveys') }}</h2>
                <a href="{{ route('dashboard.surveys.index') }}" class="text-xs font-medium text-[#A38B54] hover:underline">
                    {{ __('dashboard.dashboard.view_all') }}
                </a>
            </div>

            {{-- List rows --}}
            <div>
                @forelse($latestSurveys as $survey)
                    <div class="flex items-center justify-between py-2.5 @unless($loop->last) border-b border-[#C5C2C0]/60 @endunless">
                        <p class="truncate text-sm text-[#3D342A] flex-1">{{ $survey->title_ar }}</p>
                        <div class="ms-3 flex items-center gap-2.5 shrink-0">
                            {{-- Status badge per spec: bg #EAEAE9, text #766868, 11px, pill --}}
                            <span class="rounded-full bg-[#EAEAE9] px-2.5 py-0.5 text-[11px] text-[#766868]">
                                {{ $survey->is_active ? __('dashboard.common.active') : __('dashboard.common.inactive') }}
                            </span>
                            <a href="{{ route('dashboard.surveys.show', $survey) }}" class="text-xs font-medium text-[#A38B54] hover:underline">
                                {{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="py-10 text-center text-sm text-[#979290]">{{ __('dashboard.surveys.no_surveys') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Contact Messages --}}
        <div class="rounded-xl border border-[#B7B5B3]/60 bg-white px-4 py-3.5">
            {{-- Panel header --}}
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-[14px] font-medium text-[#3D342A]">{{ __('dashboard.dashboard.recent_messages') }}</h2>
                <a href="{{ route('dashboard.contact-messages.index') }}" class="text-xs font-medium text-[#A38B54] hover:underline">
                    {{ __('dashboard.dashboard.view_all') }}
                </a>
            </div>

            {{-- List rows --}}
            <div>
                @forelse($recentMessages as $message)
                    <div class="flex items-center justify-between py-2.5 @unless($loop->last) border-b border-[#C5C2C0]/60 @endunless">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            {{-- Message avatar: circular, bg #AEA19F, initials #3D342A, 30px --}}
                            <span class="flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-full bg-[#AEA19F] text-xs font-semibold text-[#3D342A]">
                                {{ mb_substr($message->name, 0, 1) }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm text-[#3D342A]">{{ $message->name }}</p>
                                <p class="truncate text-xs text-[#979290] mt-0.5">{{ Str::limit($message->message, 55) }}</p>
                            </div>
                        </div>
                        <div class="ms-3 flex items-center gap-1.5 shrink-0">
                            @unless($message->is_read)
                                <span class="rounded-full bg-[#A38B54]/10 px-2 py-0.5 text-[10px] font-bold text-[#A38B54]">
                                    {{ app()->getLocale() === 'ar' ? 'جديد' : 'New' }}
                                </span>
                            @endunless
                            <span class="text-[10px] text-[#979290]">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="py-10 text-center text-sm text-[#979290]">{{ __('dashboard.contact_messages.no_messages') }}</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection