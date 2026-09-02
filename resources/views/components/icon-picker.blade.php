@props([
    'name'        => 'icon',
    'value'       => '',
    'label'       => null,
    'placeholder' => 'اختر أيقونة...',
    'required'    => false,
    'hint'        => null,
])

@php
    $initialValue = old($name, $value ?? '');
    $fieldLabel   = $label ?? __('dashboard.services.icon');
@endphp

<div
    x-data="iconPicker({
        name: @js($name),
        value: @js($initialValue)
    })"
    class="space-y-1.5"
>
    {{-- Field Label --}}
    @if ($fieldLabel)
        <label class="block text-sm font-semibold text-[#3D342A] dark:text-gray-200">
            {{ $fieldLabel }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Trigger Box with Live Preview --}}
    <div
        @click="openModal()"
        class="group relative flex items-center justify-between rounded-xl border border-[#B49C6E]/40 bg-secondary dark:bg-gray-800 dark:border-gray-700 px-4 py-3 shadow-xs transition-all duration-200 hover:border-[#A38B54] hover:shadow-md cursor-pointer"
    >
        {{-- Selected Icon Preview + Label --}}
        <div class="flex items-center gap-3 overflow-hidden">
            <template x-if="selectedIcon">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#A38B54]/10 text-[#A38B54] dark:bg-[#B49C6E]/20 dark:text-[#B49C6E] shadow-2xs group-hover:scale-105 transition-transform">
                    <x-icon :name="$initialValue" class="w-6 h-6" ::name="selectedIcon" />
                </div>
            </template>

            <template x-if="!selectedIcon">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-background dark:bg-gray-700 text-gray-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
            </template>

            <div class="truncate min-w-0">
                <p x-show="selectedIconObj" class="text-sm font-bold text-[#3D342A] dark:text-background truncate" x-text="selectedIconObj ? selectedIconObj.name_ar + ' (' + selectedIconObj.id + ')' : ''"></p>
                <p x-show="!selectedIconObj && selectedIcon" class="text-sm font-bold text-[#3D342A] dark:text-background truncate" x-text="selectedIcon"></p>
                <p x-show="!selectedIcon" class="text-sm text-gray-400 dark:text-gray-500" x-text="@js($placeholder)"></p>
            </div>
        </div>

        {{-- Actions Right/Left --}}
        <div class="flex items-center gap-2 shrink-0">
            <template x-if="selectedIcon">
                <button
                    type="button"
                    @click.stop="clearSelection()"
                    class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/30 transition-colors"
                    title="إزالة الأيقونة"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </template>

            <button
                type="button"
                class="px-3 py-1.5 text-xs font-bold rounded-lg bg-[#A38B54]/10 text-[#A38B54] dark:bg-gray-700 dark:text-gray-200 group-hover:bg-[#A38B54] group-hover:text-background transition-colors"
            >
                <span x-text="selectedIcon ? 'تغيير' : 'اختر'"></span>
            </button>
        </div>
    </div>

    {{-- Field Hint --}}
    @if ($hint)
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    {{-- Hidden Form Input --}}
    <input type="hidden" :name="name" :value="selectedIcon">

    {{-- Validation Error --}}
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror

    {{-- Large Responsive Modal --}}
    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-8"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div
                x-show="open"
                x-transition.opacity.duration.200ms
                @click="cancel()"
                class="fixed inset-0 bg-black/60 backdrop-blur-xs"
            ></div>

            {{-- Dialog Box: Wider max-w-5xl layout --}}
            <div
                x-show="open"
                x-transition:enter="ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-3"
                @keydown.escape.window="cancel()"
                class="relative w-full max-w-5xl overflow-hidden rounded-2xl border border-gray-200 bg-background shadow-2xl dark:border-gray-700 dark:bg-gray-800 flex flex-col max-h-[85vh]"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-background dark:border-gray-700 px-6 py-4 bg-secondary dark:bg-gray-800 shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-[#3D342A] dark:text-background flex items-center gap-2">
                            <span>اختيار أيقونة (Lucide Icon Picker)</span>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-[#B49C6E]/20 text-[#A38B54] dark:text-[#B49C6E]" x-text="totalCount + ' أيقونة'"></span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">اختر الأيقونة المناسبة للقسم أو الخدمة</p>
                    </div>

                    <button
                        type="button"
                        @click="cancel()"
                        class="rounded-xl p-2 text-gray-400 hover:bg-background hover:text-gray-600 dark:hover:bg-gray-700 transition-colors"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Search & Categories Toolbar --}}
                <div class="p-4 bg-background/70 dark:bg-gray-900/40 border-b border-background dark:border-gray-700 space-y-3 shrink-0">
                    {{-- Search Input --}}
                    <div class="relative">
                        <input
                            type="text"
                            x-model="search"
                            x-ref="searchInput"
                            @input="page = 1"
                            placeholder="🔍 ابحث بالاسم أو الرمز (Search e.g. users, heart, award, trophy, globe)..."
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-background dark:bg-gray-800 pe-10 ps-10 py-2.5 text-sm text-gray-800 dark:text-background placeholder-gray-400 focus:border-[#A38B54] focus:ring-2 focus:ring-[#A38B54]/20 focus:outline-none"
                        />
                        <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <button
                            x-show="search"
                            @click="search = ''; page = 1"
                            type="button"
                            class="absolute inset-y-0 end-0 pe-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Category Tabs --}}
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar text-xs py-0.5">
                        <template x-for="(catName, catId) in categoryLabels" :key="catId">
                            <button
                                type="button"
                                @click="activeCategory = catId; page = 1"
                                :class="activeCategory === catId
                                    ? 'bg-[#A38B54] text-background font-bold shadow-xs'
                                    : 'bg-background dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-background dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'"
                                class="px-3.5 py-1.5 rounded-lg whitespace-nowrap transition-colors"
                                x-text="catName"
                            ></button>
                        </template>
                    </div>
                </div>

                {{-- Icon Grid Container (Scroll only inside grid, lazy slice max 24 items per page) --}}
                <div class="p-6 overflow-y-auto flex-1 max-h-[420px] custom-scrollbar space-y-4">
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                        <template x-for="iconItem in paginatedIcons" :key="iconItem.id">
                            <button
                                type="button"
                                @click="pickAndConfirm(iconItem.id)"
                                :title="iconItem.name_ar + ' (' + iconItem.id + ')'"
                                :class="tempIcon === iconItem.id
                                    ? 'border-[#A38B54] bg-[#A38B54]/10 dark:bg-[#B49C6E]/20 ring-2 ring-[#A38B54] text-[#A38B54] dark:text-[#B49C6E] font-bold shadow-xs scale-102'
                                    : 'border-gray-200 dark:border-gray-700 bg-background dark:bg-gray-800 hover:border-[#A38B54]/60 hover:bg-secondary dark:hover:bg-gray-700 hover:-translate-y-0.5 hover:shadow-xs text-gray-700 dark:text-gray-200'"
                                class="relative flex flex-col items-center justify-center p-3 rounded-xl border transition-all duration-150 cursor-pointer group aspect-square"
                            >
                                {{-- SVG Icon --}}
                                <template x-if="iconItem.svg">
                                    <div class="h-8 w-8 flex items-center justify-center transition-transform group-hover:scale-110 mb-1">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="iconItem.svg" />
                                        </svg>
                                    </div>
                                </template>

                                {{-- Emoji Fallback --}}
                                <template x-if="!iconItem.svg">
                                    <div class="h-8 w-8 flex items-center justify-center transition-transform group-hover:scale-110 mb-1">
                                        <span class="text-2xl" x-text="iconItem.emoji || iconItem.id"></span>
                                    </div>
                                </template>

                                {{-- Label --}}
                                <span class="text-[11px] font-semibold text-center truncate w-full text-gray-600 dark:text-gray-300 group-hover:text-[#A38B54]" x-text="iconItem.name_ar"></span>

                                {{-- Active Highlight Badge --}}
                                <div x-show="tempIcon === iconItem.id" class="absolute top-1.5 end-1.5 bg-[#A38B54] text-background rounded-full p-0.5 shadow-xs">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            </button>
                        </template>
                    </div>

                    {{-- Show More Lazy Loading Button if more icons exist --}}
                    <div x-show="hasMoreIcons" class="pt-3 text-center">
                        <button
                            type="button"
                            @click="loadMore()"
                            class="px-5 py-2 text-xs font-bold rounded-xl border border-[#B49C6E] bg-secondary text-[#A38B54] hover:bg-[#A38B54] hover:text-background transition-colors"
                        >
                            عرض المزيد من الأيقونات...
                        </button>
                    </div>

                    {{-- Empty Search State --}}
                    <div x-show="filteredIcons.length === 0" class="py-12 text-center space-y-3">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-background dark:bg-gray-700 text-gray-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">لم يتم العثور على أيقونات مطابقة لـ "<span x-text="search"></span>"</p>
                        <button type="button" @click="search = ''; activeCategory = 'all'; page = 1" class="text-xs text-[#A38B54] font-bold hover:underline">إعادة ضبط البحث</button>
                    </div>
                </div>

                {{-- Modal Footer with Selected Icon Live Preview & Controls --}}
                <div class="flex flex-col sm:flex-row items-center justify-between border-t border-background dark:border-gray-700 px-6 py-4 bg-background/80 dark:bg-gray-900/40 gap-3 shrink-0">
                    {{-- Selected Icon Live Preview --}}
                    <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300 w-full sm:w-auto">
                        <span class="font-medium">المعاينـة الحالية:</span>
                        <template x-if="tempIconObj">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-background dark:bg-gray-800 border border-gray-200 dark:border-gray-700 font-bold text-[#A38B54] dark:text-[#B49C6E] shadow-2xs">
                                <template x-if="tempIconObj.svg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="tempIconObj.svg" />
                                    </svg>
                                </template>
                                <span x-text="tempIconObj.name_ar + ' (' + tempIconObj.id + ')'"></span>
                            </div>
                        </template>
                        <template x-if="!tempIconObj && tempIcon">
                            <span class="font-bold text-gray-800 dark:text-background" x-text="tempIcon"></span>
                        </template>
                        <template x-if="!tempIcon">
                            <span class="text-gray-400 italic">لم يتم اختيار أيقونة</span>
                        </template>
                    </div>

                    {{-- Footer Action Buttons --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <button
                            type="button"
                            @click="clearSelection()"
                            class="px-3.5 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-colors"
                        >
                            إلغاء التحديد
                        </button>
                        <button
                            type="button"
                            @click="cancel()"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition-colors"
                        >
                            إلغاء
                        </button>
                        <button
                            type="button"
                            @click="confirmSelection()"
                            class="px-5 py-2 text-xs font-bold text-background bg-[#A38B54] hover:bg-[#8A734A] rounded-xl shadow-xs transition-colors"
                        >
                            تأكيد الاختيار
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@once('icon-picker-script')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('iconPicker', ({ name, value }) => ({
        name: name,
        open: false,
        search: '',
        activeCategory: 'all',
        selectedIcon: value || '',
        tempIcon: value || '',
        page: 1,
        perPage: 24,

        categoryLabels: {
            all: 'الكل (All)',
            general: 'عامة (General)',
            business: 'أعمال وتنمية (Business)',
            people: 'مجتمع وأفراد (People)',
            media: 'إعلام ومستندات (Media)',
            communication: 'تواصل (Communication)'
        },

        icons: [
            // General
            { id: 'home', name_ar: 'الرئيسية', name_en: 'Home', category: 'general', svg: 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25' },
            { id: 'folder', name_ar: 'مجلد برامج', name_en: 'Folder', category: 'general', svg: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z' },
            { id: 'flag', name_ar: 'علم مشاريع', name_en: 'Flag', category: 'general', svg: 'M5 3v18M5 4h11l-2 4 2 4H5' },
            { id: 'star', name_ar: 'نجمة تميز', name_en: 'Star', category: 'general', svg: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' },
            { id: 'shield', name_ar: 'درع حوكمة', name_en: 'Shield', category: 'general', svg: 'M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7l7-4z' },
            { id: 'cog', name_ar: 'إعدادات', name_en: 'Settings', category: 'general', svg: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
            { id: 'sparkles', name_ar: 'بريق الجودة', name_en: 'Sparkles', category: 'general', svg: 'M5 3v4M3 5h4M6 17v4m-2-2h4m11-16l2.286 6.857L21 12l-6.857 2.286L12 21l-2.286-6.857L3 12l6.857-2.286L12 3z' },
            { id: 'info', name_ar: 'معلومات', name_en: 'Info', category: 'general', svg: 'M12 16v-4m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            { id: 'layout', name_ar: 'تنسيق أقسام', name_en: 'Layout', category: 'general', svg: 'M3 3h18v18H3V3zm0 8h18M9 3v18' },
            { id: 'clock', name_ar: 'ساعة ووقت', name_en: 'Clock', category: 'general', svg: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
            { id: 'calendar', name_ar: 'تقويم فعاليات', name_en: 'Calendar', category: 'general', svg: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
            { id: 'target', name_ar: 'رؤية وهدف', name_en: 'Target', category: 'general', svg: 'M12 2a10 10 0 100 20 10 10 0 000-20zm0 6a4 4 0 100 8 4 4 0 000-8z' },

            // Business & Development
            { id: 'award', name_ar: 'وسام تميز', name_en: 'Award', category: 'business', svg: 'M12 15a7 7 0 1 0 0-14 7 7 0 0 0 0 14z M8.21 13.89L7 23l5-3 5 3-1.21-9.12' },
            { id: 'trophy', name_ar: 'كأس إنجاز', name_en: 'Trophy', category: 'business', svg: 'M8 21h8m-4-4v4m-6-14h12a2 2 0 012 2v2a5 5 0 01-5 5H9a5 5 0 01-5-5V5a2 2 0 012-2z' },
            { id: 'briefcase', name_ar: 'حقيبة خدمات', name_en: 'Briefcase', category: 'business', svg: 'M3 7h18M3 7v11a1 1 0 001 1h16a1 1 0 001-1V7M3 7l1.5-3h15L21 7M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2' },
            { id: 'chart', name_ar: 'إحصائيات عداد', name_en: 'Chart', category: 'business', svg: 'M3 3v18h18M7 15l4-4 3 3 5-6' },
            { id: 'lightbulb', name_ar: 'ابتكار أفكار', name_en: 'Light Bulb', category: 'business', svg: 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z' },
            { id: 'rocket', name_ar: 'انطلاق صاروخ', name_en: 'Rocket', category: 'business', svg: 'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.926 14.926 0 01-5.96 1.41 14.926 14.926 0 01-5.96-1.41' },
            { id: 'building', name_ar: 'مبنى مقرات', name_en: 'Building', category: 'business', svg: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
            { id: 'coins', name_ar: 'تبرعات مالية', name_en: 'Coins', category: 'business', svg: 'M12 10c-3.87 0-7 1.34-7 3s3.13 3 7 3 7-1.34 7-3-3.13-3-7-3z M5 13v3c0 1.66 3.13 3 7 3s7-1.34 7-3v-3 M5 16v3c0 1.66 3.13 3 7 3s7-1.34 7-3v-3' },

            // People & Community
            { id: 'users', name_ar: 'فريق مستخدمين', name_en: 'Users', category: 'people', svg: 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87' },
            { id: 'user-group', name_ar: 'مجلس إدارة', name_en: 'User Group', category: 'people', svg: 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a5.97 5.97 0 00-.942 3.197m0 0A9.093 9.093 0 012.25 18.24a3 3 0 014.682-2.72m.94 3.198l-.001.031c0 .225.012.447.037.666M12 10.5a3 3 0 100-6 3 3 0 000 6z' },
            { id: 'heart', name_ar: 'إنسانيات عطاء', name_en: 'Heart', category: 'people', svg: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
            { id: 'heart-handshake', name_ar: 'تطوع وشراكة', name_en: 'Heart Handshake', category: 'people', svg: 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z M12 5L9 8M15 8l-3-3' },
            { id: 'hand-heart', name_ar: 'أيدي العطاء', name_en: 'Hand Heart', category: 'people', svg: 'M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16 M18 11h1a2 2 0 0 1 2 2v1c0 .6-.2 1.1-.6 1.4L15 21' },
            { id: 'academic-cap', name_ar: 'تعليم وتأهيل', name_en: 'Academic Cap', category: 'people', svg: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z' },
            { id: 'gift', name_ar: 'هدايا ومساعدات', name_en: 'Gift', category: 'people', svg: 'M20 12v10H4V12 M2 7h20v5H2z M12 22V7 M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z' },

            // Media & Documents
            { id: 'newspaper', name_ar: 'أخبار صحافة', name_en: 'Newspaper', category: 'media', svg: 'M12 7v14m0-14a4 4 0 00-4-4H3v14h5a4 4 0 014 4M12 7a4 4 0 014-4h5v14h-5a4 4 0 00-4 4' },
            { id: 'clipboard', name_ar: 'استبيان تقارير', name_en: 'Clipboard', category: 'media', svg: 'M9 3h6a1 1 0 011 1v1H8V4a1 1 0 011-1zM6 5h12a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z' },
            { id: 'camera', name_ar: 'كاميرا صور', name_en: 'Camera', category: 'media', svg: 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z' },

            // Communication & Contact
            { id: 'mail', name_ar: 'بريد إلكتروني', name_en: 'Mail', category: 'communication', svg: 'M3 6h18v12H3V6zm0 0l9 7 9-7' },
            { id: 'phone', name_ar: 'هاتف واتساب', name_en: 'Phone', category: 'communication', svg: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z' },
            { id: 'globe', name_ar: 'موقع إلكتروني', name_en: 'Globe', category: 'communication', svg: 'M21 12a9 9 0 11-18 0 9 9 0 0118 0z M3.6 9h16.8 M3.6 15h16.8 M11.5 3a17 17 0 000 18 M12.5 3a17 17 0 010 18' },

            // Emojis Fallback Set
            { id: '🏠', name_ar: 'منزل (Home)', name_en: 'Home Emoji', category: 'general', emoji: '🏠' },
            { id: '👥', name_ar: 'أعضاء (Users)', name_en: 'Users Emoji', category: 'people', emoji: '👥' },
            { id: '📊', name_ar: 'مخطط (Chart)', name_en: 'Chart Emoji', category: 'business', emoji: '📊' },
            { id: '❤️', name_ar: 'إنساني (Heart)', name_en: 'Heart Emoji', category: 'people', emoji: '❤️' },
            { id: '⭐', name_ar: 'نجمة (Star)', name_en: 'Star Emoji', category: 'general', emoji: '⭐' },
            { id: '💼', name_ar: 'أعمال (Work)', name_en: 'Work Emoji', category: 'business', emoji: '💼' },
            { id: '🏢', name_ar: 'مقرات (Office)', name_en: 'Office Emoji', category: 'business', emoji: '🏢' },
            { id: '🎯', name_ar: 'هدف (Goal)', name_en: 'Goal Emoji', category: 'business', emoji: '🎯' },
            { id: '📞', name_ar: 'اتصال (Phone)', name_en: 'Phone Emoji', category: 'communication', emoji: '📞' },
            { id: '✉️', name_ar: 'رسالة (Mail)', name_en: 'Mail Emoji', category: 'communication', emoji: '✉️' }
        ],

        get totalCount() {
            return this.icons.length;
        },

        get selectedIconObj() {
            return this.icons.find(i => i.id === this.selectedIcon) || null;
        },

        get tempIconObj() {
            return this.icons.find(i => i.id === this.tempIcon) || null;
        },

        get filteredIcons() {
            let list = this.icons;
            if (this.activeCategory !== 'all') {
                list = list.filter(i => i.category === this.activeCategory);
            }
            if (this.search.trim() !== '') {
                const q = this.search.toLowerCase().trim();
                list = list.filter(i =>
                    i.id.toLowerCase().includes(q) ||
                    i.name_ar.toLowerCase().includes(q) ||
                    i.name_en.toLowerCase().includes(q)
                );
            }
            return list;
        },

        get paginatedIcons() {
            return this.filteredIcons.slice(0, this.page * this.perPage);
        },

        init() {
            this.$watch('open', () => this.refreshLucide());
            this.$watch('search', () => this.refreshLucide());
            this.$watch('activeCategory', () => this.refreshLucide());
            this.$watch('page', () => this.refreshLucide());
            this.refreshLucide();
        },

        refreshLucide() {
            this.$nextTick(() => {
                if (typeof window.lucide !== 'undefined' && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }
            });
        },

        get hasMoreIcons() {
            return this.paginatedIcons.length < this.filteredIcons.length;
        },

        loadMore() {
            this.page++;
            this.refreshLucide();
        },

        openModal() {
            this.tempIcon = this.selectedIcon;
            this.search = '';
            this.page = 1;
            this.open = true;
            this.refreshLucide();
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        },

        pickAndConfirm(id) {
            this.tempIcon = id;
            this.selectedIcon = id;
            this.open = false;
        },

        confirmSelection() {
            this.selectedIcon = this.tempIcon;
            this.open = false;
        },

        clearSelection() {
            this.selectedIcon = '';
            this.tempIcon = '';
            this.open = false;
        },

        cancel() {
            this.tempIcon = this.selectedIcon;
            this.open = false;
        }
    }));
});
</script>
@endonce
