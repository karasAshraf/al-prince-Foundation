<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_ar'                => ['required', 'string', 'max:255'],
            'title_en'                => ['nullable', 'string', 'max:255'],
            'description_ar'          => ['nullable', 'string'],
            'description_en'          => ['nullable', 'string'],
            'type_ar'                 => ['nullable', 'string', 'max:100'],
            'type_en'                 => ['nullable', 'string', 'max:100'],
            'questions'               => ['required', 'array', 'min:1'],
            'questions.*.id'          => ['required', 'string'],
            'questions.*.type'        => ['required', 'in:text,rating,select,checkbox'],
            'questions.*.label_ar'    => ['required', 'string'],
            'questions.*.label_en'    => ['nullable', 'string'],
            'questions.*.options'     => ['nullable', 'array'],
            'questions.*.options.*.ar'=> ['nullable', 'string'],
            'questions.*.options.*.en'=> ['nullable', 'string'],
            'is_active'               => ['nullable', 'boolean'],
            'starts_at'               => ['nullable', 'date'],
            'ends_at'                 => ['nullable', 'date', 'after:starts_at'],
            'external_link'           => ['nullable', 'url', 'max:500'],
            'remove_media'            => ['nullable', 'boolean'],
            'image'                   => $this->hasFile('image') ? ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'] : ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required'  => __('dashboard.surveys.survey_title') . ' (' . __('dashboard.common.required') . ')',
            'questions.required' => __('dashboard.surveys.questions') . ' (' . __('dashboard.common.required') . ')',
            'ends_at.after'      => __('dashboard.surveys.ends_at') . ' (' . __('dashboard.common.after') . ')',
        ];
    }
}

