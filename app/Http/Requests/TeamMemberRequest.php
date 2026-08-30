<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'type'          => ['required', Rule::in(['board', 'executive'])],
            'name_ar'       => ['required', 'string', 'max:255'],
            'name_en'       => ['nullable', 'string', 'max:255'],
            'position_ar'   => ['required', 'string', 'max:255'],
            'position_en'   => ['nullable', 'string', 'max:255'],
            'bio_ar'        => ['nullable', 'string', 'max:1000'],
            'bio_en'        => ['nullable', 'string', 'max:1000'],
            'external_link' => ['nullable', 'url', 'max:500'],
            'remove_media'  => ['nullable', 'boolean'],
            'photo'         => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
            'image'         => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
            'order'         => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'يجب تحديد النوع (مجلس إدارة / فريق تنفيذي)',
            'position_ar.required' => 'المسمى الوظيفي مطلوب',
            'name_ar.required'     => 'الاسم بالعربي مطلوب',
            'photo.file'           => 'يجب أن يكون ملف الوسائط ملفاً صحيحاً',
            'photo.mimes'          => 'صيغة الملف غير مدعومة. الصيغ المدعومة للصور: jpg, jpeg, png, webp وللفيديو: mp4, mov, avi, webm, mkv',
            'photo.max'            => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
            'external_link.url'    => 'يجب إدخال رابط خارجي صحيح',
        ];
    }
}