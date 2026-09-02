@extends('layouts.app')

@section('title', $program->title_ar)

@php
    $breadcrumbs = [
        ['label' => 'البرامج', 'url' => route('dashboard.programs.index')],
        ['label' => $program->title_ar, 'url' => null],
    ];
    $projectCount = $program->projects_count ?? $program->projects->count();
@endphp

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-[#3D342A]">{{ $program->title_ar }}</h1>
        <a href="{{ route('dashboard.programs.edit', $program) }}">
            <x-buttons.primary>تعديل</x-buttons.primary>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ============ MAIN COLUMN ============ --}}
        <div class="space-y-5 lg:col-span-2">

            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
                @php
                    $programImageUrl = \App\Helpers\MediaHelper::url($program, 'program_images', 'image')
                        ?? \App\Helpers\MediaHelper::resolveUrl($program->external_link ?? null);
                @endphp
                @if($programImageUrl)
                    <img
                        src="{{ $programImageUrl }}"
                        alt="{{ $program->title_ar }}"
                        class="mb-4 h-56 w-full rounded-lg object-cover"
                    >
                @endif

                <h2 class="text-lg font-semibold text-[#3D342A]">{{ $program->title_ar }}</h2>

                @if($program->title_en)
                    <p class="mt-0.5 text-sm text-[#3D342A]/50">{{ $program->title_en }}</p>
                @endif

                @if($program->description_ar)
                    <div class="prose prose-sm mt-4 max-w-none text-[#3D342A]">
                        {!! $program->description_ar !!}
                    </div>
                @endif

                @if($program->external_link)
                    <a
                        href="{{ $program->external_link }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-[#A38B54] hover:underline"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        الرابط الخارجي
                    </a>
                @endif
            </div>

            {{-- Related Projects --}}
            @if($program->projects->isNotEmpty())
                <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
                    <h3 class="mb-4 text-sm font-semibold text-[#3D342A]">
                        المشاريع المرتبطة ({{ $projectCount }})
                    </h3>
                    <ul class="divide-y divide-[#B49C6E]/10">
                        @foreach($program->projects as $project)
                            <li class="py-2.5">
                                <a
                                    href="{{ route('dashboard.projects.show', $project) }}"
                                    class="text-sm font-medium text-[#3D342A] hover:text-[#A38B54]"
                                >
                                    {{ $project->title_ar }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>

        {{-- ============ SIDEBAR COLUMN ============ --}}
        <div class="space-y-5">

            <div class="rounded-xl border border-[#B49C6E]/20 bg-secondary p-5">
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-[#3D342A]/50">الحالة</dt>
                        <dd class="mt-0.5">
                            <span @class([
                                'rounded-full px-2.5 py-1 text-xs font-medium',
                                'bg-[#B49C6E]/30 text-[#A38B54]' => $program->status === 'active',
                                'bg-secondary/60 text-[#3D342A]/70' => $program->status === 'inactive',
                            ])>
                                {{ $program->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">الترتيب</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $program->order ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">الرابط المختصر (Slug)</dt>
                        <dd class="font-mono text-xs text-[#3D342A]/70">{{ $program->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">عدد المشاريع</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $projectCount }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">آخر تحديث</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $program->updated_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#3D342A]/50">تاريخ الإنشاء</dt>
                        <dd class="font-medium text-[#3D342A]">{{ $program->created_at->format('Y-m-d') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex flex-col gap-2">
                <a href="{{ route('dashboard.programs.edit', $program) }}" class="block w-full">
                    <x-buttons.primary class="w-full justify-center">تعديل البرنامج</x-buttons.primary>
                </a>
                <form
                    method="POST"
                    action="{{ route('dashboard.programs.destroy', $program) }}"
                    x-data
                    @submit.prevent="
                        if (confirm('هل أنت متأكد من حذف هذا البرنامج؟ لا يمكن التراجع عن هذا الإجراء.')) {
                            $el.submit();
                        }
                    "
                >
                    @csrf
                    @method('DELETE')
                    <x-buttons.danger type="submit" class="w-full justify-center">حذف البرنامج</x-buttons.danger>
                </form>
            </div>

        </div>
    </div>

@endsection
