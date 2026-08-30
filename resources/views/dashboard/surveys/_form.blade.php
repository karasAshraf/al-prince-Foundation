@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- ============ MAIN COLUMN ============ --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Survey General Info --}}
        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3 mb-4">{{ __('dashboard.surveys.title') }}</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="title_ar"
                    label="{{ __('dashboard.surveys.survey_title') }} (AR)"
                    :value="$survey->title_ar ?? ''"
                    required
                />
                <x-forms.input
                    name="title_en"
                    label="{{ __('dashboard.surveys.survey_title') }} (EN)"
                    :value="$survey->title_en ?? ''"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.textarea
                    name="description_ar"
                    label="{{ __('dashboard.surveys.description') }} (AR)"
                    :value="$survey->description_ar ?? ''"
                    rows="3"
                />
                <x-forms.textarea
                    name="description_en"
                    label="{{ __('dashboard.surveys.description') }} (EN)"
                    :value="$survey->description_en ?? ''"
                    rows="3"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-forms.input
                    name="type_ar"
                    label="{{ __('dashboard.surveys.type') }} (AR)"
                    :value="$survey->type_ar ?? $survey->type ?? ''"
                    placeholder="عام"
                />
                <x-forms.input
                    name="type_en"
                    label="{{ __('dashboard.surveys.type') }} (EN)"
                    :value="$survey->type_en ?? $survey->type ?? ''"
                    placeholder="general"
                />
            </div>
        </div>

        @php
            $normalizedQuestions = [];
            $rawQuestions = old('questions', $survey->questions ?? []);
            if (empty($rawQuestions)) {
                $rawQuestions = [['id' => 'q_1', 'type' => 'text', 'label_ar' => '', 'label_en' => '', 'options' => []]];
            }
            foreach ($rawQuestions as $rawQ) {
                $normQ = [
                    'id' => $rawQ['id'] ?? 'q_' . uniqid(),
                    'type' => $rawQ['type'] ?? 'text',
                    'label_ar' => $rawQ['label_ar'] ?? '',
                    'label_en' => $rawQ['label_en'] ?? '',
                    'options' => []
                ];
                if (isset($rawQ['options']) && is_array($rawQ['options'])) {
                    foreach ($rawQ['options'] as $rawOpt) {
                        if (is_array($rawOpt)) {
                            $normQ['options'][] = [
                                'ar' => $rawOpt['ar'] ?? '',
                                'en' => $rawOpt['en'] ?? ''
                            ];
                        } else {
                            $normQ['options'][] = [
                                'ar' => $rawOpt,
                                'en' => $rawOpt
                            ];
                        }
                    }
                }
                $normalizedQuestions[] = $normQ;
            }
        @endphp

        {{-- Dynamic Questions Builder Component (Alpine.js) --}}
        <div
            x-data="{
                questions: @js($normalizedQuestions),
                addQuestion() {
                    this.questions.push({
                        id: 'q_' + (Date.now()),
                        type: 'text',
                        label_ar: '',
                        label_en: '',
                        options: [{ ar: '', en: '' }]
                    });
                },
                removeQuestion(index) {
                    if (this.questions.length > 1) {
                        this.questions.splice(index, 1);
                    }
                },
                addOption(qIndex) {
                    if (!this.questions[qIndex].options) {
                        this.questions[qIndex].options = [];
                    }
                    this.questions[qIndex].options.push({ ar: '', en: '' });
                },
                removeOption(qIndex, optIndex) {
                    this.questions[qIndex].options.splice(optIndex, 1);
                }
            }"
            class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm space-y-5"
        >
            <div class="flex items-center justify-between border-b border-[#B49C6E]/20 pb-3">
                <div>
                    <h2 class="text-base font-semibold text-[#3D342A]">{{ __('dashboard.surveys.questions') }}</h2>
                    <p class="text-xs text-[#3D342A]/60">{{ __('dashboard.surveys.questions_subtitle') }}</p>
                </div>
                <button
                    type="button"
                    @click="addQuestion()"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#A38B54] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#3D342A] transition"
                >
                    {{ __('dashboard.surveys.add_question') }}
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(q, qIndex) in questions" :key="q.id || qIndex">
                    <div class="rounded-xl border border-[#B49C6E]/30 bg-[#EAEAE9]/10 p-4 space-y-3 relative">
                        <div class="flex items-start justify-between gap-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#A38B54] text-xs font-bold text-white mt-2" x-text="qIndex + 1"></span>

                            <input type="hidden" :name="`questions[${qIndex}][id]`" :value="q.id || `q_${qIndex}`">

                            <div class="flex-grow grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input
                                    type="text"
                                    :name="`questions[${qIndex}][label_ar]`"
                                    x-model="q.label_ar"
                                    placeholder="{{ __('dashboard.surveys.question_title') }} (AR)"
                                    required
                                    class="w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none"
                                >
                                <input
                                    type="text"
                                    :name="`questions[${qIndex}][label_en]`"
                                    x-model="q.label_en"
                                    placeholder="{{ __('dashboard.surveys.question_title') }} (EN)"
                                    class="w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none"
                                >
                            </div>

                            <div class="w-40">
                                <select
                                    :name="`questions[${qIndex}][type]`"
                                    x-model="q.type"
                                    class="w-full rounded-lg border border-[#B49C6E]/40 bg-[#EAEAE9] px-3 py-2 text-sm text-[#3D342A] focus:border-[#A38B54] focus:outline-none"
                                >
                                    <option value="text">{{ __('dashboard.surveys.type_text') }}</option>
                                    <option value="rating">{{ __('dashboard.surveys.type_rating') }}</option>
                                    <option value="select">{{ __('dashboard.surveys.type_select') }}</option>
                                    <option value="checkbox">{{ __('dashboard.surveys.type_checkbox') }}</option>
                                </select>
                            </div>

                            <button
                                type="button"
                                @click="removeQuestion(qIndex)"
                                class="text-red-600 hover:text-red-800 text-xs font-medium p-1 mt-2"
                                title="{{ __('dashboard.surveys.delete_question') }}"
                            >
                                ✕
                            </button>
                        </div>

                        {{-- Options for select / checkbox --}}
                        <template x-if="q.type === 'select' || q.type === 'checkbox'">
                            <div class="mt-3 border-t border-[#B49C6E]/20 pt-3 space-y-2 pr-8">
                                <label class="text-xs font-semibold text-[#3D342A]">{{ __('dashboard.surveys.options') }}</label>
                                <template x-for="(opt, optIndex) in (q.options || [])" :key="optIndex">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-grow grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <input
                                                type="text"
                                                :name="`questions[${qIndex}][options][${optIndex}][ar]`"
                                                x-model="opt.ar"
                                                placeholder="{{ __('dashboard.surveys.option_placeholder') }} (AR)"
                                                required
                                                class="w-full rounded-lg border border-[#B49C6E]/30 bg-[#EAEAE9] px-3 py-1.5 text-xs text-[#3D342A]"
                                            >
                                            <input
                                                type="text"
                                                :name="`questions[${qIndex}][options][${optIndex}][en]`"
                                                x-model="opt.en"
                                                placeholder="{{ __('dashboard.surveys.option_placeholder') }} (EN)"
                                                class="w-full rounded-lg border border-[#B49C6E]/30 bg-[#EAEAE9] px-3 py-1.5 text-xs text-[#3D342A]"
                                            >
                                        </div>
                                        <button
                                            type="button"
                                            @click="removeOption(qIndex, optIndex)"
                                            class="text-red-500 hover:text-red-700 text-xs shrink-0"
                                        >
                                            {{ __('dashboard.surveys.delete_option') }}
                                        </button>
                                    </div>
                                </template>

                                <button
                                    type="button"
                                    @click="addOption(qIndex)"
                                    class="mt-1 text-xs text-[#A38B54] font-semibold hover:underline"
                                >
                                    {{ __('dashboard.surveys.add_option') }}
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            @error('questions')
                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- ============ SIDEBAR COLUMN ============ --}}
    <div class="space-y-5">

        <div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-semibold text-[#3D342A] border-b border-[#B49C6E]/20 pb-3">{{ __('dashboard.surveys.timing_and_status') }}</h3>

            <div class="space-y-3">
                <x-forms.date-picker
                    name="starts_at"
                    label="{{ __('dashboard.surveys.starts_at') }}"
                    :value="$survey->starts_at?->format('Y-m-d')"
                />

                <x-forms.date-picker
                    name="ends_at"
                    label="{{ __('dashboard.surveys.ends_at') }}"
                    :value="$survey->ends_at?->format('Y-m-d')"
                />
            </div>

            <div class="pt-2 border-t border-[#B49C6E]/20">
                <x-forms.checkbox
                    name="is_active"
                    label="{{ __('dashboard.surveys.is_active_label') }}"
                    :checked="$survey->is_active ?? true"
                />
            </div>
        </div>

        <x-media-upload
            name="image"
            url-name="external_link"
            label="{{ __('dashboard.surveys.media_label') }}"
            :current-url="isset($survey) ? \App\Helpers\MediaHelper::url($survey, 'survey_images', 'image') : null"
            :current-external-url="isset($survey) ? ($survey->getFirstMedia('survey_images')?->getCustomProperty('external_url')) : null"
        />

        <div class="flex flex-col gap-3">

            <x-buttons.primary type="submit" class="w-full justify-center">{{ __('dashboard.surveys.save_survey') }}</x-buttons.primary>
            <a href="{{ route('dashboard.surveys.index') }}" class="w-full">
                <x-buttons.secondary type="button" class="w-full justify-center">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
            </a>
        </div>

    </div>

</div>
