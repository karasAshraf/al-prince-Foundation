<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'program_id'      => ['nullable', 'exists:programs,id'],
            'title_ar'        => ['required', 'string', 'max:255'],
            'title_en'        => ['nullable', 'string', 'max:255'],
            'slug'            => ['nullable', 'string', 'max:255', Rule::unique('projects', 'slug')->ignore($projectId)],
            'description_ar'  => ['nullable', 'string'],
            'description_en'  => ['nullable', 'string'],
            'goal_ar'         => ['nullable', 'string'],
            'goal_en'         => ['nullable', 'string'],
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'],
            'project_status'  => ['required', Rule::in(['ongoing', 'completed'])],
            'status'          => ['required', Rule::in(['draft', 'published'])],
            'external_link'   => ['nullable', 'url', 'max:500'],
            'media_external_link' => ['nullable', 'url', 'max:500'],
            'remove_media'    => ['nullable', 'boolean'],
            'image'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
            'meta_title_ar'       => ['nullable', 'string', 'max:255'],
            'meta_title_en'       => ['nullable', 'string', 'max:255'],
            'meta_description_ar' => ['nullable', 'string'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_keywords'       => ['nullable', 'string', 'max:255'],
            'canonical_url'       => ['nullable', 'url', 'max:500'],
            'og_image'            => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'عنوان المشروع بالعربي مطلوب',
            'program_id.exists' => 'البرنامج المختار غير موجود',
            'image.file'        => 'يجب أن يكون ملف الوسائط ملفاً صحيحاً',
            'image.mimes'       => 'صيغة الملف غير مدعومة. الصيغ المدعومة للصور: jpg, jpeg, png, webp وللفيديو: mp4, mov, avi, webm, mkv',
            'image.max'         => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
            'external_link.url' => 'يجب إدخال رابط خارجي صحيح',
        ];
    }
}

