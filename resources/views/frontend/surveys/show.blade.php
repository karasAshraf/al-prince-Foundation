@php
    $locale = app()->getLocale();
    $title  = $survey->title;
    $desc   = $survey->description;
    $mediaUrl = \App\Helpers\MediaHelper::url($survey, 'survey_images', 'image', 'detail');
    $questions = is_array($survey->questions) ? $survey->questions : [];
@endphp

<x-frontend-layout :title="$title">

    <div class="max-w-3xl mx-auto space-y-8"
         x-data="surveyForm()"
         @submit.prevent="submitForm($event)">
         
        <!-- Page Header / Intro -->
        <div class="text-center space-y-4">
            <x-frontend.badge variant="secondary">
                {{ $survey->type ?: __('frontend.survey_impact_assessment') }}
            </x-frontend.badge>
            
            <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-surface leading-tight">
                {{ $title }}
            </h1>
            
            @if ($desc)
                <p class="text-base text-text-primary/75 dark:text-gray-300 max-w-xl mx-auto leading-relaxed font-sans">
                    {{ $desc }}
                </p>
            @endif

            <!-- Step count progress indicator -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">
                <x-icon name="list-ordered" class="w-3.5 h-3.5" />
                <span>
                    {{ $locale === 'ar' ? 'عدد الأسئلة: ' . count($questions) : 'Total Questions: ' . count($questions) }}
                </span>
            </div>
        </div>

        @if ($mediaUrl)
            <div class="overflow-hidden rounded-3xl border border-primary-light/20 max-h-96 shadow-sm">
                <img src="{{ $mediaUrl }}" alt="{{ $title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Success Message (Alpine handled) -->
        <div x-show="success" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-3xl p-8 sm:p-12 text-center space-y-6 shadow-md select-none">
            
            <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto shadow-sm">
                <svg class="w-10 h-10 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-xl sm:text-2xl font-bold text-text-primary dark:text-surface">
                    {{ $locale === 'ar' ? 'شكرًا لمشاركتك!' : 'Thank You for Your Feedback!' }}
                </h3>
                <p class="text-sm sm:text-base text-text-primary/70 dark:text-gray-300 max-w-md mx-auto leading-relaxed">
                    {{ $locale === 'ar' ? 'تم إرسال إجابتك بنجاح ومساهمتك تساعدنا في التطوير والتحسين.' : 'Your responses have been successfully submitted. Your contribution helps us improve.' }}
                </p>
            </div>
            
            <div class="pt-4">
                <a href="{{ route('surveys.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-primary text-white font-semibold hover:bg-primary/95 transition-all active:scale-[0.98]">
                    {{ $locale === 'ar' ? 'العودة إلى الاستبيانات' : 'Back to Surveys' }}
                </a>
            </div>
        </div>

        <!-- Form container (Alpine controls visibility) -->
        <form x-show="!success" action="{{ route('surveys.response.store') }}" method="POST"
              class="bg-white dark:bg-gray-800 border border-primary-light/20 rounded-3xl p-6 sm:p-10 space-y-8 shadow-sm relative">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey->id }}">

            <!-- Honeypot check -->
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

            <!-- Contact Info Section -->
            <div class="rounded-2xl border border-primary-light/15 bg-secondary-light/5 dark:bg-gray-900/40 p-6 space-y-4">
                <h3 class="text-base font-bold text-text-primary dark:text-gray-100 flex items-center gap-2">
                    <x-icon name="user" class="w-4.5 h-4.5 text-primary" />
                    {{ $locale === 'ar' ? 'معلومات التواصل' : 'Contact Information' }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label for="respondent_name" class="block text-xs font-semibold text-text-primary/80 dark:text-gray-300">
                            {{ $locale === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}
                        </label>
                        <input type="text" name="respondent_name" id="respondent_name" required
                               placeholder="{{ $locale === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}"
                               x-bind:disabled="submitting"
                               class="w-full px-4 py-3 rounded-xl border border-primary-light/30 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary text-sm text-text-primary dark:text-gray-100">
                        <template x-if="errors.respondent_name">
                            <p class="text-xs text-red-500 mt-1 font-semibold" x-text="errors.respondent_name[0]"></p>
                        </template>
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1.5">
                        <label for="respondent_phone" class="block text-xs font-semibold text-text-primary/80 dark:text-gray-300">
                            {{ $locale === 'ar' ? 'رقم الجوال *' : 'Phone Number *' }}
                        </label>
                        <input type="tel" name="respondent_phone" id="respondent_phone" required
                               placeholder="05xxxxxxxx"
                               x-bind:disabled="submitting"
                               class="w-full px-4 py-3 rounded-xl border border-primary-light/30 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary text-sm text-text-primary dark:text-gray-100 text-start direction-ltr">
                        <template x-if="errors.respondent_phone">
                            <p class="text-xs text-red-500 mt-1 font-semibold" x-text="errors.respondent_phone[0]"></p>
                        </template>
                    </div>
                </div>

                <!-- Optional Email -->
                <div class="space-y-1.5 pt-2">
                    <label for="respondent_email" class="block text-xs font-semibold text-text-primary/80 dark:text-gray-300">
                        {{ $locale === 'ar' ? 'البريد الإلكتروني (اختياري)' : 'Email Address (Optional)' }}
                    </label>
                    <input type="email" name="respondent_email" id="respondent_email"
                           placeholder="example@domain.com"
                           x-bind:disabled="submitting"
                           class="w-full px-4 py-3 rounded-xl border border-primary-light/30 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary text-sm text-text-primary dark:text-gray-100">
                    <template x-if="errors.respondent_email">
                        <p class="text-xs text-red-500 mt-1 font-semibold" x-text="errors.respondent_email[0]"></p>
                    </template>
                </div>
            </div>

            <!-- Dynamic Questions -->
            <div class="space-y-8">
                @forelse ($questions as $index => $q)
                    @php
                        $qText = is_array($q) ? ($locale === 'ar' ? ($q['label_ar'] ?? $q['label_en'] ?? $q['title_ar'] ?? $q['question'] ?? '') : ($q['label_en'] ?? $q['label_ar'] ?? $q['title_en'] ?? $q['question'] ?? '')) : $q;
                        $qType = is_array($q) ? ($q['type'] ?? 'text') : 'text';
                        $qOpts = is_array($q) ? ($q['options'] ?? []) : [];
                    @endphp

                    <div class="space-y-4 p-5 sm:p-6 rounded-3xl bg-surface/40 dark:bg-gray-900/40 border border-primary-light/15 relative">
                        
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center shrink-0 w-7 h-7 rounded-full bg-primary/10 text-primary text-xs font-bold font-mono shadow-sm">
                                {{ $index + 1 }}
                            </span>
                            <label class="block text-base font-bold text-text-primary dark:text-gray-100 leading-snug">
                                {{ $qText }}
                            </label>
                        </div>

                        @if (($qType === 'select' || $qType === 'choice') && !empty($qOpts))
                            <div class="grid grid-cols-1 gap-2 pt-1">
                                @foreach ($qOpts as $optIndex => $opt)
                                    @php
                                        $optText = is_array($opt) ? ($locale === 'ar' ? ($opt['ar'] ?? $opt['en'] ?? '') : ($opt['en'] ?? $opt['ar'] ?? '')) : $opt;
                                    @endphp
                                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-primary-light/20 dark:border-gray-700 bg-surface/20 dark:bg-gray-900/20 hover:bg-secondary-light/30 dark:hover:bg-primary-light/10 cursor-pointer transition-all duration-300 text-sm relative group">
                                        <input type="radio" name="answers[{{ $index }}]" value="{{ $optIndex }}"
                                               x-bind:disabled="submitting"
                                               class="peer sr-only" required>
                                        
                                        <!-- Custom radio circle -->
                                        <div class="w-5 h-5 rounded-full border border-primary-light/40 bg-white dark:bg-gray-800 flex items-center justify-center shrink-0 peer-checked:border-primary peer-checked:bg-primary transition-all">
                                            <div class="w-2 h-2 rounded-full bg-white scale-0 peer-checked:scale-100 transition-transform"></div>
                                        </div>
                                        
                                        <!-- Custom background highlight on checked -->
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-primary/60 peer-checked:bg-primary/5 pointer-events-none transition-all"></div>
                                        
                                        <span class="relative z-10 text-text-primary/90 dark:text-gray-200 font-medium select-none">{{ $optText }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($qType === 'checkbox' && !empty($qOpts))
                            <div class="grid grid-cols-1 gap-2 pt-1">
                                @foreach ($qOpts as $optIndex => $opt)
                                    @php
                                        $optText = is_array($opt) ? ($locale === 'ar' ? ($opt['ar'] ?? $opt['en'] ?? '') : ($opt['en'] ?? $opt['ar'] ?? '')) : $opt;
                                    @endphp
                                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-primary-light/20 dark:border-gray-700 bg-surface/20 dark:bg-gray-900/20 hover:bg-secondary-light/30 dark:hover:bg-primary-light/10 cursor-pointer transition-all duration-300 text-sm relative group">
                                        <input type="checkbox" name="answers[{{ $index }}][]" value="{{ $optIndex }}"
                                               x-bind:disabled="submitting"
                                               class="peer sr-only">
                                        
                                        <!-- Custom checkbox box -->
                                        <div class="w-5 h-5 rounded-lg border border-primary-light/40 bg-white dark:bg-gray-800 flex items-center justify-center shrink-0 peer-checked:border-primary peer-checked:bg-primary transition-all">
                                            <svg class="w-3.5 h-3.5 text-white scale-0 peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        
                                        <!-- Custom background highlight on checked -->
                                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-primary/60 peer-checked:bg-primary/5 pointer-events-none transition-all"></div>
                                        
                                        <span class="relative z-10 text-text-primary/90 dark:text-gray-200 font-medium select-none">{{ $optText }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($qType === 'rating')
                            <div class="flex items-center gap-3 pt-1 flex-wrap">
                                @for ($r = 1; $r <= 5; $r++)
                                    <label class="relative flex flex-col items-center justify-center w-12 h-12 rounded-xl border border-primary-light/20 dark:border-gray-700 bg-surface/20 dark:bg-gray-900/20 hover:bg-secondary-light/30 dark:hover:bg-primary-light/10 cursor-pointer text-text-primary dark:text-gray-200 transition-all duration-300 group">
                                        <input type="radio" name="answers[{{ $index }}]" value="{{ $r }}"
                                               x-bind:disabled="submitting"
                                               class="peer sr-only" required>
                                        <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-primary/60 peer-checked:bg-primary/10 pointer-events-none transition-all"></div>
                                        <span class="relative z-10 text-sm font-bold peer-checked:text-primary transition-colors">{{ $r }}</span>
                                    </label>
                                @endfor
                            </div>
                        @else
                            <textarea name="answers[{{ $index }}]" rows="3" required
                                      x-bind:disabled="submitting"
                                      placeholder="{{ __('frontend.write_answer_here') }}"
                                      class="w-full px-4 py-3 rounded-2xl border border-primary-light/30 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary text-sm text-text-primary dark:text-gray-100"></textarea>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-sm text-text-primary/50 py-4">
                        {{ __('frontend.no_survey_questions') }}
                    </p>
                @endforelse
            </div>

            @if (!empty($questions))
                <div class="pt-4 text-center">
                    <button type="submit"
                            x-bind:disabled="submitting"
                            class="w-full sm:w-auto px-10 py-3.5 rounded-2xl bg-primary text-white font-semibold hover:bg-primary/95 transition-all shadow-md active:scale-[0.98] disabled:bg-gray-400 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                        <template x-if="submitting">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
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
