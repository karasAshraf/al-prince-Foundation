@extends('layouts.app')

@section('title', $project->title_ar)

@php
    $breadcrumbs = [
        ['label' => __('dashboard.projects.title'), 'url' => route('dashboard.projects.index')],
        ['label' => $project->title_ar, 'url' => null],
    ];
@endphp

@section('content')

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#3D342A]">{{ $project->title_ar }}</h1>
            @if($project->title_en)
                <p class="mt-0.5 text-sm text-[#3D342A]/50">{{ $project->title_en }}</p>
            @endif
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('dashboard.projects.edit', $project) }}">
                <x-buttons.primary>{{ __('dashboard.common.edit') }}</x-buttons.primary>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ============ MAIN COLUMN ============ --}}
        <div class="space-y-5 lg:col-span-2">

            {{-- Cover Image + Content --}}
            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">

                @php $projectCoverUrl = \App\Helpers\MediaHelper::url($project, 'project_images', 'image'); @endphp
                @if($projectCoverUrl)
                    <img
                        src="{{ $projectCoverUrl }}"
                        alt="{{ $project->title_ar }}"
                        class="mb-5 h-60 w-full rounded-lg object-cover"
                    >
                @endif

                @if($project->description_ar)
                    <div>
                        <h2 class="mb-2 text-sm font-semibold text-[#3D342A]/70">{{ __('dashboard.projects.description') }}</h2>
                        <div class="prose prose-sm max-w-none text-[#3D342A]">
                            {!! $project->description_ar !!}
                        </div>
                    </div>
                @endif

                @if($project->goal_ar)
                    <div class="mt-5 border-t border-[#B49C6E]/10 pt-5">
                        <h2 class="mb-2 text-sm font-semibold text-[#3D342A]/70">{{ __('dashboard.programs.goals') }}</h2>
                        <div class="prose prose-sm max-w-none text-[#3D342A]">
                            {!! $project->goal_ar !!}
                        </div>
                    </div>
                @endif

                @if($project->external_link)
                    <div class="mt-5 border-t border-[#B49C6E]/10 pt-4">
                        <a
                            href="{{ $project->external_link }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-[#A38B54] hover:underline"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                            {{ __('dashboard.common.file') }}
                        </a>
                    </div>
                @endif
            </div>

        </div>

        {{-- ============ SIDEBAR COLUMN ============ --}}
        <div class="space-y-5">

            {{-- Metadata Card --}}
            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
                <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">{{ __('dashboard.projects.show') }}</h3>
                <dl class="space-y-3 text-sm">

                    {{-- Program --}}
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.programs.single') }}</dt>
                        <dd class="mt-0.5 font-medium text-[#3D342A]">
                            @if($project->program)
                                <a href="{{ route('dashboard.programs.show', $project->program) }}" class="hover:text-[#A38B54]">
                                    {{ $project->program->title_ar }}
                                </a>
                            @else
                                <span class="text-[#3D342A]/40">—</span>
                            @endif
                        </dd>
                    </div>

                    {{-- Publish Status --}}
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.common.status') }}</dt>
                        <dd class="mt-0.5">
                            <span @class([
                                'rounded-full px-2.5 py-1 text-xs font-medium',
                                'bg-[#B49C6E]/30 text-[#A38B54]'    => $project->status === 'published',
                                'bg-secondary/60 text-[#3D342A]/70' => $project->status === 'draft',
                            ])>
                                {{ $project->status === 'published' ? __('dashboard.common.published') : __('dashboard.common.draft') }}
                            </span>
                        </dd>
                    </div>

                    {{-- Project Status --}}
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.projects.status') }}</dt>
                        <dd class="mt-0.5">
                            <span @class([
                                'rounded-full px-2.5 py-1 text-xs font-medium',
                                'bg-blue-100 text-blue-700'         => $project->project_status === 'ongoing',
                                'bg-[#B49C6E]/30 text-[#A38B54]'    => $project->project_status === 'completed',
                            ])>
                                {{ $project->project_status === 'ongoing' ? __('dashboard.projects.in_progress') : __('dashboard.projects.completed') }}
                            </span>
                        </dd>
                    </div>

                    {{-- Dates --}}
                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.projects.start_date') }}</dt>
                        <dd class="font-medium text-[#3D342A]">
                            {{ $project->start_date?->format('Y-m-d') ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.projects.end_date') }}</dt>
                        <dd class="font-medium text-[#3D342A]">
                            {{ $project->end_date?->format('Y-m-d') ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[#3D342A]/50">Slug</dt>
                        <dd class="font-mono text-xs text-[#3D342A]/70">{{ $project->slug }}</dd>
                    </div>

                    <div>
                        <dt class="text-[#3D342A]/50">{{ __('dashboard.common.updated_at') }}</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $project->updated_at->format('Y-m-d H:i') }}</dd>
                    </div>

                </dl>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-2">
                <a href="{{ route('dashboard.projects.edit', $project) }}" class="block w-full">
                    <x-buttons.primary class="w-full justify-center">{{ __('dashboard.common.edit') }}</x-buttons.primary>
                </a>
                <form
                    method="POST"
                    action="{{ route('dashboard.projects.destroy', $project) }}"
                    x-data
                    @submit.prevent="
                        if (confirm('{{ __('dashboard.common.confirm_delete_message') }}')) {
                            $el.submit();
                        }
                    "
                >
                    @csrf
                    @method('DELETE')
                    <x-buttons.danger type="submit" class="w-full justify-center">{{ __('dashboard.common.delete') }}</x-buttons.danger>
                </form>
            </div>

        </div>
    </div>

@endsection
