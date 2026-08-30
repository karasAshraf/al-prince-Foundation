@if($surveys->isEmpty())
    <x-tables.empty-state
        title="{{ __('dashboard.surveys.no_surveys') }}"
        message="{{ __('dashboard.common.empty_state') }}"
        action-label="+ {{ __('dashboard.surveys.create') }}"
        :action-url="route('dashboard.surveys.create')"
    />
@else
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($surveys as $item)
            <div class="flex flex-col justify-between rounded-2xl border border-[#B49C6E]/20 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="space-y-4">
                    {{-- Title and Type badge --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-[#3D342A] leading-snug truncate-2-lines">
                                <a href="{{ route('dashboard.surveys.show', $item) }}" class="hover:text-[#A38B54] transition-colors">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            @if(app()->getLocale() === 'ar' && $item->title_en)
                                <span class="block text-xs text-[#3D342A]/50 mt-1 truncate">{{ $item->title_en }}</span>
                            @elseif(app()->getLocale() === 'en' && $item->title_ar)
                                <span class="block text-xs text-[#3D342A]/50 mt-1 truncate">{{ $item->title_ar }}</span>
                            @endif
                        </div>
                        @if($item->type)
                            <span class="shrink-0 rounded-full bg-[#EAEAE9]/60 px-2.5 py-0.5 text-xs font-semibold text-[#3D342A]">
                                {{ $item->type }}
                            </span>
                        @endif
                    </div>

                    {{-- Stats details --}}
                    <div class="grid grid-cols-2 gap-4 border-t border-b border-[#B49C6E]/10 py-3">
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold text-[#3D342A]/40 tracking-wider">
                                {{ __('dashboard.surveys.questions') }}
                            </span>
                            <div class="flex items-center gap-1 text-sm font-bold text-[#3D342A]/80">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ is_array($item->questions) ? count($item->questions) : 0 }}</span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-[10px] uppercase font-bold text-[#3D342A]/40 tracking-wider">
                                {{ __('dashboard.surveys.responses') }}
                            </span>
                            <div class="flex items-center gap-1 text-sm font-bold text-[#3D342A]/80">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 3a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 6a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>{{ $item->responses_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Date range & status --}}
                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between text-[#3D342A]/60">
                            <span>{{ app()->getLocale() === 'ar' ? 'تاريخ البدء / الانتهاء:' : 'Dates (Start / End):' }}</span>
                            <span class="font-medium text-end">
                                {{ $item->starts_at?->format('Y-m-d') ?: '—' }} / {{ $item->ends_at?->format('Y-m-d') ?: '—' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#3D342A]/60">{{ __('dashboard.common.status') }}</span>
                            <x-tables.status-toggle
                                :id="$item->id"
                                :is-active="$item->is_active"
                                :route="route('dashboard.surveys.toggle-status', $item)"
                            />
                        </div>
                    </div>
                </div>

                {{-- Card footer buttons (CTA and Actions) --}}
                <div class="mt-6 flex items-center justify-between gap-3 border-t border-[#B49C6E]/10 pt-4">
                    {{-- Prominent Survey Analysis detail link --}}
                    <a href="{{ route('dashboard.surveys.analysis', $item) }}" 
                       class="inline-flex items-center gap-1.5 rounded-xl bg-[#A38B54] px-3.5 py-2 text-xs font-bold text-white hover:bg-[#8A734A] transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2zm0 0v-7a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z" />
                        </svg>
                        <span>{{ app()->getLocale() === 'ar' ? 'إحصائيات الإجابات' : 'Survey Analysis' }}</span>
                    </a>

                    <x-tables.table-actions
                        :show-url="route('dashboard.surveys.show', $item)"
                        :edit-url="route('dashboard.surveys.edit', $item)"
                        :delete-action="route('dashboard.surveys.destroy', $item)"
                        :item-label="$item->title"
                    />
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        <x-tables.pagination :paginator="$surveys" />
    </div>
@endif
