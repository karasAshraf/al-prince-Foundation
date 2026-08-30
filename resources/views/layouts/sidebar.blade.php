{{--
    Sidebar — primary dashboard navigation.
    Reads `sidebarOpen` from the parent shell (mobile drawer visibility).
    Owns its own `collapsed` state (desktop icon-only rail), persisted
    in localStorage so it survives page navigation.

    Palette: dark brown #3D342A background, inactive links #C5C2C0,
    active link text #B49C6E on #4A4038 pill highlight.
--}}
<aside
    x-data="{
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        toggleCollapse() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar-collapsed', this.collapsed);
        }
    }"
    :class="collapsed ? 'lg:w-20' : 'lg:w-[220px]'"
    class="fixed inset-y-0 start-0 z-40 flex w-[220px] flex-col bg-[#372828] transition-all duration-300 lg:static lg:h-full shrink-0"
    x-bind:class="sidebarOpen ? 'translate-x-0' : (document.documentElement.dir === 'rtl' ? 'translate-x-full lg:translate-x-0' : '-translate-x-full lg:translate-x-0')"
    aria-label="{{ __('dashboard.common.sidebar_aria') }}"
>
    {{-- Desktop collapse toggle --}}
    <div class="hidden items-center justify-end border-b border-white/10 px-3 py-3 lg:flex shrink-0">
        <button
            type="button"
            @click="toggleCollapse()"
            class="rounded-md p-1.5 text-[#AC8321] hover:bg-white/5 hover:text-[#B8974F]"
            :aria-label="collapsed ? '{{ __('dashboard.common.sidebar_expand') }}' : '{{ __('dashboard.common.sidebar_collapse') }}'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="collapsed ? (document.documentElement.dir === 'rtl' ? '-rotate-180' : 'rotate-180') : (document.documentElement.dir === 'rtl' ? 'rotate-180' : '')" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto custom-scrollbar px-2.5 py-4">

        @php
            // Nav groups — adding a module is just one line here
            $navGroups = [
                [
                    'label' => __('dashboard.sidebar.content_management'),
                    'items' => [
                        ['label' => __('dashboard.sidebar.services'), 'route' => 'dashboard.services.index', 'active' => 'dashboard.services.*', 'icon' => 'briefcase'],
                        ['label' => __('dashboard.sidebar.activities'), 'route' => 'dashboard.activities.index', 'active' => 'dashboard.activities.*', 'icon' => 'spark'],
                        ['label' => __('dashboard.sidebar.industries'), 'route' => 'dashboard.industries.index', 'active' => 'dashboard.industries.*', 'icon' => 'building'],
                        ['label' => __('dashboard.sidebar.solutions'), 'route' => 'dashboard.solutions.index', 'active' => 'dashboard.solutions.*', 'icon' => 'bulb'],
                        [
                            'label' => __('dashboard.sidebar.advertising_center'),
                            'icon' => 'advertising',
                            'is_dropdown' => true,
                            'children' => [
                                ['label' => __('dashboard.sidebar.news'), 'route' => 'dashboard.news.index', 'active' => 'dashboard.news.*', 'icon' => 'newspaper'],
                                ['label' => __('dashboard.sidebar.events'), 'route' => 'dashboard.events.index', 'active' => 'dashboard.events.*', 'icon' => 'calendar'],
                                ['label' => __('dashboard.sidebar.media_library'), 'route' => 'dashboard.media-library.index', 'active' => 'dashboard.media-library.*', 'icon' => 'folder-download'],
                            ]
                        ],
                        ['label' => __('dashboard.sidebar.programs'), 'route' => 'dashboard.programs.index', 'active' => 'dashboard.programs.*', 'icon' => 'folder'],
                        ['label' => __('dashboard.sidebar.projects'), 'route' => 'dashboard.projects.index', 'active' => 'dashboard.projects.*', 'icon' => 'flag'],
                        ['label' => __('dashboard.sidebar.about_us'), 'route' => 'dashboard.about-sections.index', 'active' => 'dashboard.about-sections.*', 'icon' => 'info'],
                        ['label' => __('dashboard.sidebar.organizational_structure'), 'route' => 'dashboard.organizational-structure.edit', 'active' => 'dashboard.organizational-structure.edit', 'icon' => 'layout'],
                        ['label' => __('dashboard.sidebar.team_members'), 'route' => 'dashboard.team-members.index', 'active' => 'dashboard.team-members.*', 'icon' => 'users'],
                        ['label' => __('dashboard.sidebar.home_sections'), 'route' => 'dashboard.home-sections.index', 'active' => 'dashboard.home-sections.*', 'icon' => 'layout'],
                        ['label' => __('dashboard.sidebar.hero_slides'), 'route' => 'dashboard.hero-slides.index', 'active' => 'dashboard.hero-slides.*', 'icon' => 'layout'],
                        ['label' => __('dashboard.sidebar.partners'), 'route' => 'dashboard.partners.index', 'active' => 'dashboard.partners.*', 'icon' => 'handshake'],
                    ],
                ],
                [
                    'label' => __('dashboard.sidebar.governance_participation'),
                    'items' => [
                        ['label' => __('dashboard.sidebar.governance_center'), 'route' => 'dashboard.governance-documents.index', 'active' => 'dashboard.governance-documents.*', 'icon' => 'shield'],
                        ['label' => __('dashboard.sidebar.surveys'), 'route' => 'dashboard.surveys.index', 'active' => 'dashboard.surveys.*', 'icon' => 'clipboard'],
                        ['label' => __('dashboard.sidebar.contact_messages'), 'route' => 'dashboard.contact-messages.index', 'active' => 'dashboard.contact-messages.*', 'icon' => 'mail'],
                    ],
                ],
                [
                    'label' => __('dashboard.sidebar.system'),
                    'items' => array_filter([
                        auth()->user()->hasRole('admin') ? ['label' => __('dashboard.sidebar.users'), 'route' => 'dashboard.users.index', 'active' => 'dashboard.users.*', 'icon' => 'user-group'] : null,
                        ['label' => __('dashboard.sidebar.settings'), 'route' => 'dashboard.settings.index', 'active' => 'dashboard.settings.*', 'icon' => 'cog'],
                    ]),
                ],
            ];

            // SVG icon path library
            $icons = [
                'newspaper'  => 'M12 7v14m0-14a4 4 0 00-4-4H3v14h5a4 4 0 014 4M12 7a4 4 0 014-4h5v14h-5a4 4 0 00-4 4',
                'folder'     => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
                'flag'       => 'M5 3v18M5 4h11l-2 4 2 4H5',
                'briefcase'  => 'M3 7h18M3 7v11a1 1 0 001 1h16a1 1 0 001-1V7M3 7l1.5-3h15L21 7M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2',
                'spark'      => 'M13 10V3L4 14h7v7l9-11h-7z',
                'building'   => 'M3 21h18M3 21V7l9-4 9 4v14M9 21V9m6 12V9m-3 0V3',
                'bulb'       => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                'info'       => 'M12 16v-4m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'users'      => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87',
                'layout'     => 'M3 3h18v18H3V3zm0 8h18M9 3v18',
                'shield'     => 'M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7l7-4z',
                'clipboard'  => 'M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1zM6 5h12a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z',
                'mail'       => 'M3 6h18v12H3V6zm0 0l9 7 9-7',
                'user-group' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87',
                'cog'        => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                'calendar'   => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'folder-download' => 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'advertising'=> 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
                'handshake'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 3a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 6a2 2 0 11-4 0 2 2 0 014 0z',
                'chart'      => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2zm0 0v-7a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z',
            ];
        @endphp

        @foreach ($navGroups as $idx => $group)
            @if (count($group['items']))
                <div class="@if($idx > 0) mt-6 @else mt-2 @endif">
                    {{-- Section label — hidden when collapsed on desktop --}}
                    <p
                        x-show="!collapsed"
                        class="mb-1.5 px-2.5 text-[11px] font-semibold uppercase tracking-wider text-[#B4AEA4]"
                    >
                        {{ $group['label'] }}
                    </p>

                    <ul class="space-y-0.5">
                        @php
                            $groupItems = $group['items'];
                            if ($idx === 0) {
                                array_unshift($groupItems, [
                                    'label'  => __('dashboard.sidebar.analytics') ?? (app()->getLocale() === 'ar' ? 'التحليلات والإحصائيات' : 'Analytics & Stats'),
                                    'route'  => 'dashboard.analytics',
                                    'active' => 'dashboard.analytics',
                                    'icon'   => 'chart',
                                ]);
                            }
                        @endphp

                        @foreach ($groupItems as $item)
                            @if (isset($item['is_dropdown']) && $item['is_dropdown'])
                                @php
                                    $anyChildActive = false;
                                    foreach ($item['children'] as $child) {
                                        if (request()->routeIs($child['active'])) {
                                            $anyChildActive = true;
                                            break;
                                        }
                                    }
                                @endphp
                                <li x-data="{ open: @js($anyChildActive) }">
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        @class([
                                            'group flex w-full items-center justify-between rounded-lg py-[9px] text-[13px] font-medium transition-all duration-200',
                                            'bg-[#A5780A] text-[#F5F5F5]' => $anyChildActive,
                                            'text-[#F5F5F5]/60 hover:bg-white/5 hover:text-[#B8974F]' => !$anyChildActive,
                                        ])
                                        :class="collapsed ? 'justify-center px-[10px]' : 'px-[10px] gap-2'"
                                    >
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] shrink-0 text-[#AC8321]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
                                            </svg>
                                            <span x-show="!collapsed" class="truncate">{{ $item['label'] }}</span>
                                        </div>
                                        <svg
                                            x-show="!collapsed"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-3.5 w-3.5 shrink-0 text-[#AC8321] transition-transform duration-200"
                                            :class="open ? 'rotate-180' : ''"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <ul
                                        x-show="open && !collapsed"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-cloak
                                        class="mt-0.5 space-y-0.5 ps-3 border-s border-white/10 ms-4"
                                    >
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a
                                                    href="{{ route($child['route']) }}"
                                                    @class([
                                                        'group flex items-center gap-2 rounded-lg px-[10px] py-[9px] text-[13px] font-medium transition-all duration-200',
                                                        'bg-[#A5780A] text-[#F5F5F5]' => request()->routeIs($child['active']),
                                                        'text-[#F5F5F5]/60 hover:bg-white/5 hover:text-[#B8974F]' => !request()->routeIs($child['active']),
                                                    ])
                                                    :title="collapsed ? '{{ $child['label'] }}' : null"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] shrink-0 text-[#AC8321]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$child['icon']] }}" />
                                                    </svg>
                                                    <span x-show="!collapsed" class="truncate">{{ $child['label'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a
                                        href="{{ route($item['route']) }}"
                                        @class([
                                            'group flex items-center rounded-lg py-[9px] text-[13px] font-medium transition-all duration-200',
                                            'bg-[#A5780A] text-[#F5F5F5]' => request()->routeIs($item['active']),
                                            'text-[#F5F5F5]/60 hover:bg-white/5 hover:text-[#B8974F]' => ! request()->routeIs($item['active']),
                                        ])
                                        :class="collapsed ? 'justify-center px-[10px]' : 'px-[10px] gap-2'"
                                        :title="collapsed ? '{{ $item['label'] }}' : null"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] shrink-0 text-[#AC8321]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
                                        </svg>
                                        <span x-show="!collapsed" class="truncate">{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    </nav>
</aside>