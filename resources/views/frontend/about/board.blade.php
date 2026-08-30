{{-- ╔══════════════════════════════════════════════════════════════════╗ --}}
{{-- ║  Board of Directors Page                                        ║ --}}
{{-- ║  Data: $boardMembers (TeamMember collection, type=board)        ║ --}}
{{-- ╚══════════════════════════════════════════════════════════════════╝ --}}

<x-frontend-layout title="{{ __('frontend.board_of_directors') }}">

    @php $isRtl = app()->getLocale() === 'ar'; @endphp

    {{-- ── Page Hero Slot (outside main container) ──────────────────────── --}}
    <x-slot:hero>
        <div class="relative w-full py-16 sm:py-20 md:py-24 bg-cover bg-center bg-no-repeat overflow-hidden"
             style="background-image: url('{{ asset('storage/backgroundSolution/board.png') }}');">
            {{-- Professional gradient overlay using brand colors and dark tones --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/95 via-primary/85 to-primary-light/70 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-black/40"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                {{-- Eyebrow --}}
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-secondary-light mb-3 block">
                    {{ __('frontend.governance_and_management') }}
                </span>

                {{-- Page title --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight mb-4">
                    {{ __('frontend.board_of_directors') }}
                </h1>

                {{-- Subtle decorative brand element --}}
                <div class="flex items-center justify-center gap-2 mt-4 mb-6" aria-hidden="true">
                    <div class="h-0.5 w-12 bg-secondary-light/35 rounded-full"></div>
                    <div class="w-2 h-2 rounded-full bg-secondary-light"></div>
                    <div class="h-0.5 w-12 bg-secondary-light/35 rounded-full"></div>
                </div>

                {{-- Description --}}
                <p class="mt-4 text-base sm:text-lg text-white/90 max-w-2xl mx-auto leading-relaxed">
                    {{ __('frontend.board_members_desc') }}
                </p>
            </div>
        </div>
    </x-slot:hero>

    {{-- ── Board Introduction & Responsibilities ───────────────────────── --}}
    <section class="mb-16 space-y-12 mt-8" x-data="{ inView: false, activeTab: 0 }" x-intersect.once="inView = true">
        
        {{-- Two-Column Intro: Primary Card vs Content Area --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            {{-- Right / Content Area (RTL: appears on the right on desktop, visually first. Mobile: second) --}}
            <div class="order-2 lg:order-1 lg:col-span-7 flex flex-col justify-center bg-white dark:bg-gray-800 border border-primary-light/15 dark:border-gray-700/60 p-6 sm:p-8 md:p-10 rounded-3xl shadow-sm transition-all duration-700 transform"
                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                <div class="space-y-4 max-w-2xl">
                    <p class="text-base sm:text-lg text-text-primary/80 dark:text-gray-300 leading-relaxed font-medium text-justify">
                        {{ $isRtl ? 'يمثل مجلس الأمناء السلطة العليا في المؤسسة، ويتولى الإشراف على توجهاتها الاستراتيجية، واعتماد سياساتها وخططها، ومتابعة أدائها، وضمان التزامها برسالتها وأهدافها ولائحتها الأساسية.' : 'The Board of Trustees represents the supreme authority of the Foundation, supervising its strategic directions, adopting its policies and plans, monitoring its performance, and ensuring its commitment to its mission, goals, and bylaws.' }}
                    </p>
                </div>
            </div>

            {{-- Left / Primary Card (RTL: appears on the left on desktop, visually second. Mobile: first) --}}
            <div class="order-1 lg:order-2 lg:col-span-5 relative overflow-hidden bg-primary text-white p-6 sm:p-8 md:p-10 rounded-3xl shadow-sm flex flex-col justify-between transition-all duration-700 transform delay-200"
                 :class="inView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">
                
                {{-- Decorative Shapes --}}
                <div class="absolute -end-10 -top-10 w-40 h-40 rounded-full bg-gradient-to-br from-white/10 to-transparent blur-2xl pointer-events-none"></div>
                <div class="absolute -start-10 -bottom-10 w-40 h-40 rounded-full bg-gradient-to-tr from-secondary-light/20 to-transparent blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 space-y-4">
                    <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider bg-[#EAEAE9] text-primary rounded-full">
                        {{ $isRtl ? 'الهيكل القيادي' : 'Leadership Structure' }}
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                        {{ $isRtl ? 'مجلس الأمناء ودوره الاستراتيجي' : 'Board of Trustees & Its Strategic Role' }}
                    </h3>
                </div>
                
                <div class="relative z-10 mt-8 flex justify-between items-center">
                    <span class="text-xs text-white/70">
                        {{ $isRtl ? 'مؤسسة الأمير عبد الرحمن' : 'Prince Abdulrahman Foundation' }}
                    </span>
                    <div class="w-10 h-10 rounded-full bg-[#EAEAE9] flex items-center justify-center text-primary font-black shadow-sm">
                        {{ $isRtl ? 'الأمير' : 'P' }}
                    </div>
                </div>
            </div>
            
        </div>

        {{-- Responsibilities Header --}}
        <div class="text-center pt-4">
            <h3 class="inline-flex flex-col items-center text-lg sm:text-xl lg:text-2xl font-bold text-text-primary dark:text-surface gap-2">
                <span>{{ $isRtl ? 'ويؤدي المجلس دوراً محورياً في:' : 'The Board plays a pivotal role in:' }}</span>
                <span class="w-20 h-1 bg-[#EAEAE9] rounded-full"></span>
            </h3>
        </div>

        {{-- Responsibilities Tabs / Dropdown Interface --}}
        @php
            $responsibilities = [
                [
                    'title_ar' => 'تحديد الاتجاه الاستراتيجي',
                    'title_en' => 'Determining Strategic Direction',
                    'desc_ar' => 'يتولى المجلس صياغة واعتماد الأهداف طويلة المدى ورسم التوجهات الكبرى التي تقود المؤسسة نحو تحقيق رؤيتها بنجاح.',
                    'desc_en' => 'The board is responsible for formulating and adopting long-term goals and outlining the major directions that lead the foundation to successfully achieve its vision.',
                    'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'title_ar' => 'اعتماد الخطط والبرامج',
                    'title_en' => 'Adopting Plans and Programs',
                    'desc_ar' => 'مراجعة وإقرار المبادرات والمشاريع التنموية الكبرى والخطط التشغيلية والسنوية لضمان توافقها مع الرسالة العامة.',
                    'desc_en' => 'Reviewing and approving major developmental initiatives, projects, and operational/annual plans to ensure alignment with the overall mission.',
                    'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                ],
                [
                    'title_ar' => 'الإشراف على الحوكمة والالتزام',
                    'title_en' => 'Supervising Governance & Compliance',
                    'desc_ar' => 'تأسيس ورقابة تطبيق منظومة حوكمة صارمة تضمن الالتزام بالقوانين واللوائح وتعزز الشفافية والمساءلة المؤسسية.',
                    'desc_en' => 'Establishing and monitoring the implementation of a strict governance framework that ensures compliance with laws and regulations and enhances corporate transparency and accountability.',
                    'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.956 11.956 0 0112 5.714z',
                ],
                [
                    'title_ar' => 'متابعة الأداء المالي والمؤسسي',
                    'title_en' => 'Monitoring Financial & Institutional Performance',
                    'desc_ar' => 'التقييم المستمر للمركز المالي للمؤسسة واعتماد الموازنات السنوية والتقارير المالية الدورية بدقة متناهية.',
                    'desc_en' => 'Continuous assessment of the foundation’s financial position and the approval of annual budgets and periodic financial reports with extreme accuracy.',
                    'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
                ],
                [
                    'title_ar' => 'حماية أصول المؤسسة واستدامتها',
                    'title_en' => 'Protecting Assets & Sustainability',
                    'desc_ar' => 'الإشراف على إدارة وحفظ أصول المؤسسة وممتلكاتها وتطوير الخطط لضمان استمرار مواردها وتنميتها.',
                    'desc_en' => 'Oversight of the management and preservation of the foundation’s assets and properties, developing plans to secure and grow its resource base.',
                    'icon' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253',
                ],
                [
                    'title_ar' => 'الإشراف على المخاطر',
                    'title_en' => 'Risk Management & Oversight',
                    'desc_ar' => 'تحديد وتحليل المخاطر المحتملة (المالية والتشغيلية والقانونية) ووضع استراتيجيات وقائية للتعامل معها.',
                    'desc_en' => 'Identifying and analyzing potential risks (financial, operational, and legal) and putting in place preventive strategies to address them.',
                    'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
                ],
                [
                    'title_ar' => 'دعم الشراكات والعلاقات الاستراتيجية',
                    'title_en' => 'Supporting Partnerships & Strategic Relations',
                    'desc_ar' => 'المساهمة في بناء وتوطيد علاقات تعاون وثيقة مع الشركاء المحليين والدوليين لتعزيز وصول وأثر برامجنا.',
                    'desc_en' => 'Contributing to building and strengthening close collaborative relationships with local and international partners to expand the reach and impact of our programs.',
                    'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
                ],
                [
                    'title_ar' => 'تعيين ومتابعة الإدارة التنفيذية',
                    'title_en' => 'Appointing & Following Executive Management',
                    'desc_ar' => 'اختيار وتعيين الكفاءات القيادية للإدارة التنفيذية ومتابعة مؤشرات أدائهم التشغيلي والمالي بانتظام.',
                    'desc_en' => 'Selecting and appointing leadership talent for the executive management team and regularly reviewing their operational and financial performance indicators.',
                    'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                ],
            ];
        @endphp

        {{-- Mobile Dropdown Selector --}}
        <div class="block lg:hidden w-full max-w-md mx-auto mb-6">
            <label for="board-role-select" class="block text-sm font-bold text-text-primary dark:text-gray-300 mb-2">
                {{ $isRtl ? 'اختر أحد أدوار مجلس الأمناء:' : 'Select one of the Board roles:' }}
            </label>
            <select id="board-role-select" x-model.number="activeTab" class="w-full p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-2xl text-text-primary dark:text-gray-100 font-bold focus:outline-none focus:ring-2 focus:ring-primary">
                @foreach ($responsibilities as $index => $resp)
                    <option value="{{ $index }}">{{ $isRtl ? $resp['title_ar'] : $resp['title_en'] }}</option>
                @endforeach
            </select>
        </div>

        {{-- Desktop Horizontal Tabs --}}
        <div class="hidden lg:flex flex-wrap gap-2.5 justify-center max-w-5xl mx-auto mb-8">
            @foreach ($responsibilities as $index => $resp)
                <button @click="activeTab = {{ $index }}"
                        class="px-5 py-3 rounded-xl font-bold text-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm flex items-center gap-2"
                        :class="activeTab === {{ $index }} ? 'bg-primary text-white ring-2 ring-[#EAEAE9]/50' : 'bg-white dark:bg-gray-800 text-text-primary dark:text-gray-300 border border-gray-200/80 dark:border-gray-700/60 hover:bg-gray-50 dark:hover:bg-gray-700/40'">
                    
                    {{-- Small dot indicator on active --}}
                    <span class="w-2 h-2 rounded-full transition-all duration-200"
                          :class="activeTab === {{ $index }} ? 'bg-[#EAEAE9]' : 'bg-gray-300 dark:bg-gray-600'"></span>
                          
                    <span>{{ $isRtl ? $resp['title_ar'] : $resp['title_en'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- Active Responsibility Content Panel --}}
        <div class="relative bg-white dark:bg-gray-800 border border-primary-light/15 dark:border-gray-700/60 p-8 sm:p-10 rounded-3xl shadow-sm text-center max-w-3xl mx-auto overflow-hidden">
            @foreach ($responsibilities as $index => $resp)
                <div x-show="activeTab === {{ $index }}"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6 flex flex-col items-center">
                    
                    {{-- Icon Container --}}
                    <div class="w-16 h-16 rounded-2xl bg-[#EAEAE9] text-primary flex items-center justify-center shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $resp['icon'] }}"/>
                        </svg>
                    </div>

                    {{-- Title and Explanation --}}
                    <div class="space-y-3 max-w-xl">
                        <h4 class="text-xl sm:text-2xl font-black text-primary dark:text-primary-light">
                            {{ $isRtl ? $resp['title_ar'] : $resp['title_en'] }}
                        </h4>
                        <p class="text-base text-text-primary/75 dark:text-gray-300 leading-relaxed font-medium">
                            {{ $isRtl ? $resp['desc_ar'] : $resp['desc_en'] }}
                        </p>
                    </div>
                    
                </div>
            @endforeach
        </div>
        
    </section>

    {{-- ── Member List ──────────────────────────────────────────────────── --}}
    @if ($boardMembers->isEmpty())

        {{-- Empty state --}}
        <x-frontend.empty-state
            :title="__('frontend.no_board_members')"
            :description="__('frontend.content_coming_soon')"
        >
            <x-slot:action>
                <x-frontend.button
                    :href="route('about.index')"
                    variant="outline"
                    size="sm"
                >
                    {{ $isRtl ? '→' : '←' }} {{ __('frontend.back_to_about') }}
                </x-frontend.button>
            </x-slot:action>
        </x-frontend.empty-state>

    @else

        <section aria-label="{{ __('frontend.board_of_directors') }}">
            {{-- Two-column grid on large screens; single column on mobile --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                @foreach ($boardMembers as $member)
                    <x-frontend.team-card :member="$member" layout="list" />
                @endforeach
            </div>
        </section>

    @endif

    {{-- ── Back Link ────────────────────────────────────────────────────── --}}
    <div class="text-center mt-14">
        <x-frontend.button
            :href="route('about.index')"
            variant="ghost"
            size="md"
        >
            <svg class="w-4 h-4 inline-block me-1 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('frontend.back_to_about') }}
        </x-frontend.button>
    </div>

</x-frontend-layout>
