@php
    $locale = app()->getLocale();
    $title  = $survey->title;
    $desc   = $survey->description;
    $mediaUrl = \App\Helpers\MediaHelper::url($survey, 'survey_images', 'image', 'detail');
    $questions = is_array($survey->questions) ? $survey->questions : [];
    $totalQuestions = count($questions);
@endphp

<x-frontend-layout :title="$title">

    <div class="max-w-3xl mx-auto space-y-8"
         x-data="surveyForm()"
         @submit.prevent="submitForm($event)">

        {{-- ── Page Header / Intro ──────────────────────────────────────── --}}
        <div class="text-center space-y-4">
            <x-frontend.badge variant="secondary">
                {{ $survey->type ?: __('frontend.survey_impact_assessment') }}
            </x-frontend.badge>

            <h1 class="text-3xl sm:text-4xl font-extrabold text-text-primary dark:text-background leading-tight tracking-tight">
                {{ $title }}
            </h1>

            @if ($desc)
                <p class="text-base text-text-primary/75 dark:text-gray-300 max-w-xl mx-auto leading-relaxed font-sans">
                    {{ $desc }}
                </p>
            @endif

            {{-- Question count pill --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold bg-primary/10 text-primary border border-primary/20">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                <span>
                    {{ $locale === 'ar' ? 'عدد الأسئلة: ' . $totalQuestions : 'Total Questions: ' . $totalQuestions }}
                </span>
            </div>
        </div>

        {{-- ── Survey Image ─────────────────────────────────────────────── --}}
        @if ($mediaUrl)
            <div class="overflow-hidden rounded-3xl border border-secondary/20 max-h-96 shadow-sm">
                <img src="{{ $mediaUrl }}" alt="{{ $title }}" class="w-full h-full object-cover">
            </div>
        @endif

        {{-- ── Success Message (Alpine controlled) ─────────────────────── --}}
        <div x-show="success" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-background dark:bg-gray-800 border border-secondary/20 rounded-3xl p-8 sm:p-12 text-center space-y-6 shadow-md select-none">

            <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto shadow-sm">
                <svg class="w-10 h-10 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>

            <div class="space-y-2">
                <h3 class="text-xl sm:text-2xl font-bold text-text-primary dark:text-background">
                    {{ $locale === 'ar' ? 'شكرًا لمشاركتك!' : 'Thank You for Your Feedback!' }}
                </h3>
                <p class="text-sm sm:text-base text-text-primary/70 dark:text-gray-300 max-w-md mx-auto leading-relaxed">
                    {{ $locale === 'ar' ? 'تم إرسال إجابتك بنجاح ومساهمتك تساعدنا في التطوير والتحسين.' : 'Your responses have been successfully submitted. Your contribution helps us improve.' }}
                </p>
            </div>

            <div class="pt-4">
                <a href="{{ route('surveys.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-primary text-background font-semibold hover:bg-primary/95 transition-all active:scale-[0.98]">
                    {{ $locale === 'ar' ? 'العودة إلى الاستبيانات' : 'Back to Surveys' }}
                </a>
            </div>
        </div>

        {{-- ── Form (Alpine hides on success) ─────────────────────────── --}}
        <form x-show="!success"
              action="{{ route('surveys.response.store') }}" method="POST"
              class="space-y-6 relative">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey->id }}">

            {{-- Honeypot --}}
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

            {{-- ── Thin progress bar (Alpine-driven, optional enhancement) ── --}}
            @if ($totalQuestions > 0)
                <div class="sticky top-0 z-10 -mx-4 sm:-mx-6 bg-background/95 dark:bg-gray-900/95 backdrop-blur-sm px-4 sm:px-6 py-3 border-b border-secondary/20 shadow-sm"
                     x-data="{ answered: 0 }"
                     x-init="
                         $watch('answered', () => {});
                         document.addEventListener('change', () => {
                             answered = document.querySelectorAll('[name^=\'answers\']:checked, [name^=\'answers\'][type=\'text\'], textarea[name^=\'answers\']').length;
                         });
                     ">
                    <div class="flex items-center justify-between text-xs font-semibold text-text-primary/60 dark:text-gray-400 mb-1.5">
                        <span>
                            {{ $locale === 'ar' ? 'تقدمك في الاستبيان' : 'Your Progress' }}
                        </span>
                        <span class="text-primary font-bold">
                            {{ $totalQuestions }}
                            {{ $locale === 'ar' ? 'سؤال' : 'Questions' }}
                        </span>
                    </div>
                    <div class="w-full bg-secondary/30 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500"
                             :style="`width: ${Math.min(100, Math.round((answered / {{ $totalQuestions }}) * 100))}%`">
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Contact Information ──────────────────────────────────── --}}
            <div class="rounded-2xl border border-secondary/20 bg-secondary/5 dark:bg-gray-900/40 p-6 sm:p-8 space-y-5 shadow-sm">
                <h3 class="text-base sm:text-lg font-extrabold text-text-primary dark:text-background flex items-center gap-2 border-b border-secondary/20 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </span>
                    {{ $locale === 'ar' ? 'معلومات التواصل' : 'Contact Information' }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Full Name --}}
                    <div class="space-y-2">
                        <label for="respondent_name"
                               class="block text-sm font-bold text-text-primary/90 dark:text-gray-200">
                            {{ $locale === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
                            <span class="text-primary ms-0.5">*</span>
                        </label>
                        <input type="text" name="respondent_name" id="respondent_name" required
                               placeholder="{{ $locale === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}"
                               x-bind:disabled="submitting"
                               class="w-full px-4 py-3.5 rounded-xl border-2 border-secondary/30 bg-background dark:bg-gray-800
                                      focus:outline-none focus:ring-0 focus:border-primary
                                      text-sm text-text-primary dark:text-background
                                      placeholder:text-text-primary/40 dark:placeholder:text-gray-500
                                      transition-colors duration-200">
                        <template x-if="errors.respondent_name">
                            <p class="text-xs text-red-500 mt-1 font-semibold" x-text="errors.respondent_name[0]"></p>
                        </template>
                    </div>

                    {{-- Phone Number --}}
                    <div class="space-y-2">
                        <label for="respondent_phone"
                               class="block text-sm font-bold text-text-primary/90 dark:text-gray-200">
                            {{ $locale === 'ar' ? 'رقم الجوال' : 'Phone Number' }}
                            <span class="text-primary ms-0.5">*</span>
                        </label>
                        <input type="tel" name="respondent_phone" id="respondent_phone" required
                               placeholder="{{ app()->getLocale() === 'ar' ? '05XXXXXXXX أو +9665XXXXXXXX' : '05XXXXXXXX or +9665XXXXXXXX' }}"
                               x-bind:disabled="submitting"
                               class="w-full px-4 py-3.5 rounded-xl border-2 border-secondary/30 bg-background dark:bg-gray-800
                                      focus:outline-none focus:ring-0 focus:border-primary
                                      text-sm text-text-primary dark:text-background text-start direction-ltr
                                      placeholder:text-text-primary/40 dark:placeholder:text-gray-500
                                      transition-colors duration-200">
                        <template x-if="errors.respondent_phone">
                            <p class="text-xs text-red-500 mt-1 font-semibold" x-text="errors.respondent_phone[0]"></p>
                        </template>
                    </div>
                </div>

                {{-- Optional Email --}}
                <div class="space-y-2">
                    <label for="respondent_email"
                           class="block text-sm font-bold text-text-primary/90 dark:text-gray-200">
                        {{ $locale === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
                        <span class="text-text-primary/40 font-normal text-xs ms-1">
                            ({{ $locale === 'ar' ? 'اختياري' : 'Optional' }})
                        </span>
                    </label>
                    <input type="email" name="respondent_email" id="respondent_email"
                           placeholder="example@domain.com"
                           x-bind:disabled="submitting"
                           class="w-full px-4 py-3.5 rounded-xl border-2 border-secondary/30 bg-background dark:bg-gray-800
                                  focus:outline-none focus:ring-0 focus:border-primary
                                  text-sm text-text-primary dark:text-background
                                  placeholder:text-text-primary/40 dark:placeholder:text-gray-500
                                  transition-colors duration-200">
                    <template x-if="errors.respondent_email">
                        <p class="text-xs text-red-500 mt-1 font-semibold" x-text="errors.respondent_email[0]"></p>
                    </template>
                </div>
            </div>

            {{-- ── Dynamic Questions ─────────────────────────────────────── --}}
            <div class="space-y-6">
                @forelse ($questions as $index => $q)
                    @php
                        $qText = is_array($q)
                            ? ($locale === 'ar'
                                ? ($q['label_ar'] ?? $q['label_en'] ?? $q['title_ar'] ?? $q['question'] ?? '')
                                : ($q['label_en'] ?? $q['label_ar'] ?? $q['title_en'] ?? $q['question'] ?? ''))
                            : $q;
                        $qType = is_array($q) ? ($q['type'] ?? 'text') : 'text';
                        $qOpts = is_array($q) ? ($q['options'] ?? []) : [];
                    @endphp

                    {{-- ── Question Card ───────────────────────────────── --}}
                    <div class="p-6 sm:p-8 rounded-3xl bg-background dark:bg-gray-800
                                border-2 border-secondary/20 dark:border-gray-700
                                shadow-sm hover:shadow-md hover:border-primary/25
                                transition-all duration-300 space-y-5 relative">

                        {{-- Question header: number + text ──────────────── --}}
                        <div class="flex items-start gap-4">
                            {{-- Larger, higher-contrast number badge --}}
                            <span class="flex items-center justify-center shrink-0
                                         w-9 h-9 rounded-full
                                         bg-primary text-background
                                         text-sm font-extrabold font-mono shadow-sm">
                                {{ $index + 1 }}
                            </span>
                            <label class="block text-lg sm:text-xl font-bold
                                          text-text-primary dark:text-background
                                          leading-snug pt-0.5">
                                {{ $qText }}
                            </label>
                        </div>

                        {{-- ── Choice / Select (radio) ─────────────────── --}}
                        @if (($qType === 'select' || $qType === 'choice') && !empty($qOpts))
                            <div class="grid grid-cols-1 gap-3 pt-1">
                                @foreach ($qOpts as $optIndex => $opt)
                                    @php
                                        $optText = is_array($opt)
                                            ? ($locale === 'ar'
                                                ? ($opt['ar'] ?? $opt['en'] ?? '')
                                                : ($opt['en'] ?? $opt['ar'] ?? ''))
                                            : $opt;
                                    @endphp
                                    <label class="flex items-center gap-4 p-4 rounded-2xl
                                                  border-2 border-secondary/25 dark:border-gray-700
                                                  bg-background/60 dark:bg-gray-900/30
                                                  hover:border-primary/40 hover:bg-primary/5 dark:hover:bg-primary/10
                                                  cursor-pointer transition-all duration-200
                                                  group relative">
                                        <input type="radio"
                                               name="answers[{{ $index }}]"
                                               value="{{ $optIndex }}"
                                               x-bind:disabled="submitting"
                                               class="peer sr-only" required>

                                        {{-- Custom radio circle --}}
                                        <div class="w-5 h-5 rounded-full border-2 border-secondary/40 bg-background dark:bg-gray-800
                                                    flex items-center justify-center shrink-0
                                                    peer-checked:border-primary peer-checked:bg-primary
                                                    transition-all duration-200">
                                            <div class="w-2.5 h-2.5 rounded-full bg-white scale-0 peer-checked:scale-100 transition-transform duration-200"></div>
                                        </div>

                                        {{-- Full-row highlight on checked --}}
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent
                                                    peer-checked:border-primary peer-checked:bg-primary/8
                                                    dark:peer-checked:bg-primary/15
                                                    pointer-events-none transition-all duration-200"></div>

                                        <span class="relative z-10 text-sm sm:text-base text-text-primary/90 dark:text-gray-200 font-medium select-none
                                                     peer-checked:text-primary peer-checked:font-semibold transition-colors duration-200">
                                            {{ $optText }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                        {{-- ── Checkbox ─────────────────────────────────── --}}
                        @elseif ($qType === 'checkbox' && !empty($qOpts))
                            <div class="grid grid-cols-1 gap-3 pt-1">
                                @foreach ($qOpts as $optIndex => $opt)
                                    @php
                                        $optText = is_array($opt)
                                            ? ($locale === 'ar'
                                                ? ($opt['ar'] ?? $opt['en'] ?? '')
                                                : ($opt['en'] ?? $opt['ar'] ?? ''))
                                            : $opt;
                                    @endphp
                                    <label class="flex items-center gap-4 p-4 rounded-2xl
                                                  border-2 border-secondary/25 dark:border-gray-700
                                                  bg-background/60 dark:bg-gray-900/30
                                                  hover:border-primary/40 hover:bg-primary/5 dark:hover:bg-primary/10
                                                  cursor-pointer transition-all duration-200
                                                  group relative">
                                        <input type="checkbox"
                                               name="answers[{{ $index }}][]"
                                               value="{{ $optIndex }}"
                                               x-bind:disabled="submitting"
                                               class="peer sr-only">

                                        {{-- Custom checkbox --}}
                                        <div class="w-5 h-5 rounded-md border-2 border-secondary/40 bg-background dark:bg-gray-800
                                                    flex items-center justify-center shrink-0
                                                    peer-checked:border-primary peer-checked:bg-primary
                                                    transition-all duration-200">
                                            <svg class="w-3 h-3 text-white scale-0 peer-checked:scale-100 transition-transform duration-200 stroke-[3]"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>

                                        {{-- Full-row highlight on checked --}}
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent
                                                    peer-checked:border-primary peer-checked:bg-primary/8
                                                    dark:peer-checked:bg-primary/15
                                                    pointer-events-none transition-all duration-200"></div>

                                        <span class="relative z-10 text-sm sm:text-base text-text-primary/90 dark:text-gray-200 font-medium select-none
                                                     peer-checked:text-primary peer-checked:font-semibold transition-colors duration-200">
                                            {{ $optText }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                        {{-- ── Rating (1–5) ──────────────────────────────── --}}
                        @elseif ($qType === 'rating')
                            <div class="flex items-center gap-3 pt-1 flex-wrap">
                                {{-- Low label --}}
                                <span class="text-xs font-semibold text-text-primary/50 dark:text-gray-400 shrink-0">
                                    {{ $locale === 'ar' ? 'ضعيف' : 'Poor' }}
                                </span>
                                @for ($r = 1; $r <= 5; $r++)
                                    <label class="relative flex flex-col items-center justify-center
                                                  w-14 h-14 rounded-2xl
                                                  border-2 border-secondary/30 dark:border-gray-700
                                                  bg-background/60 dark:bg-gray-900/30
                                                  hover:border-primary/50 hover:bg-primary/8 dark:hover:bg-primary/15
                                                  cursor-pointer transition-all duration-200 group">
                                        <input type="radio"
                                               name="answers[{{ $index }}]"
                                               value="{{ $r }}"
                                               x-bind:disabled="submitting"
                                               class="peer sr-only" required>
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent
                                                    peer-checked:border-primary peer-checked:bg-primary/10
                                                    dark:peer-checked:bg-primary/20
                                                    pointer-events-none transition-all duration-200"></div>
                                        <span class="relative z-10 text-lg font-extrabold
                                                     text-text-primary/70 dark:text-gray-400
                                                     peer-checked:text-primary
                                                     group-hover:text-primary
                                                     transition-colors duration-200">
                                            {{ $r }}
                                        </span>
                                    </label>
                                @endfor
                                {{-- High label --}}
                                <span class="text-xs font-semibold text-text-primary/50 dark:text-gray-400 shrink-0">
                                    {{ $locale === 'ar' ? 'ممتاز' : 'Excellent' }}
                                </span>
                            </div>

                        {{-- ── Text / Textarea ───────────────────────────── --}}
                        @else
                            <textarea name="answers[{{ $index }}]" rows="4" required
                                      x-bind:disabled="submitting"
                                      placeholder="{{ __('frontend.write_answer_here') }}"
                                      class="w-full px-4 py-3.5 rounded-2xl
                                             border-2 border-secondary/30 dark:border-gray-700 bg-background dark:bg-gray-800
                                             focus:outline-none focus:ring-0 focus:border-primary
                                             text-sm sm:text-base text-text-primary dark:text-background
                                             placeholder:text-text-primary/35 dark:placeholder:text-gray-500
                                             transition-colors duration-200 resize-none"></textarea>
                        @endif
                    </div>{{-- /question card --}}

                @empty
                    <p class="text-center text-sm text-text-primary/50 py-4">
                        {{ __('frontend.no_survey_questions') }}
                    </p>
                @endforelse
            </div>{{-- /questions --}}

            {{-- ── Submit Button ────────────────────────────────────────── --}}
            @if (!empty($questions))
                <div class="pt-4 text-center">
                    <button type="submit"
                            x-bind:disabled="submitting"
                            class="w-full sm:w-auto px-12 py-4 rounded-2xl
                                   bg-primary text-background
                                   font-bold text-base tracking-wide
                                   hover:bg-primary/95 shadow-md hover:shadow-lg
                                   transition-all duration-200 active:scale-[0.98]
                                   disabled:bg-gray-400 disabled:cursor-not-allowed
                                   inline-flex items-center justify-center gap-2.5">
                        <template x-if="submitting">
                            <svg class="animate-spin h-5 w-5 text-background" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="submitting ? '{{ $locale === 'ar' ? 'جاري الإرسال...' : 'Submitting...' }}' : '{{ __('frontend.submit_answers') }}'"></span>
                    </button>
                </div>
            @endif
        </form>
    </div>

</x-frontend-layout>

<script>
    function surveyForm() {
        return {
            submitting: false,
            success: false,
            errors: {},
            submitForm(e) {
                this.submitting = true;
                this.errors = {};

                let formData = new FormData(e.target);

                fetch(e.target.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        this.success = true;
                        this.submitting = false;
                        // Scroll to the top of the container
                        window.scrollTo({
                            top: e.target.parentElement.offsetTop - 40,
                            behavior: 'smooth'
                        });
                    } else if (response.status === 422) {
                        response.json().then(data => {
                            this.errors = data.errors || {};
                            this.submitting = false;

                            // Scroll to first error
                            this.$nextTick(() => {
                                let firstError = document.querySelector('.text-red-500');
                                if (firstError) {
                                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            });
                        });
                    } else {
                        this.submitting = false;
                    }
                })
                .catch(err => {
                    this.submitting = false;
                });
            }
        }
    }
</script>
