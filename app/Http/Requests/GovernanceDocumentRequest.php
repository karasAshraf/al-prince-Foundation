<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GovernanceDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title_ar'      => ['required', 'string', 'max:255'],
            'title_en'      => ['nullable', 'string', 'max:255'],
            'category'      => ['required', Rule::in(['policies', 'financial_reports', 'achievement_reports'])],
            'fiscal_year'   => ['required', 'integer', 'min:2000', 'max:2100'],
            'external_link' => ['nullable', 'url', 'max:500'],
            'remove_media'  => ['nullable', 'boolean'],
            'file'          => [$this->isMethod('POST') && !$this->filled('external_link') ? 'required' : 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
            'image'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv,ogg', 'max:51200'],
            'order'         => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'العنوان بالعربي مطلوب',
            'category.required' => 'التصنيف مطلوب',
            'file.file'         => 'يجب أن يكون الملف ملفاً صحيحاً',
            'file.mimes'        => 'صيغة الملف غير مدعومة. الصيغ المدعومة: PDF, JPG, PNG, WEBP, MP4, MOV, AVI, WEBM, MKV',
            'file.max'          => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
            'external_link.url' => 'يجب إدخال رابط خارجي صحيح',
        ];
    }
}

