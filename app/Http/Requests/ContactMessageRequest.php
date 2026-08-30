<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s-]+$/'],
            'subject'  => ['nullable', 'string', 'max:255'],
            'message'  => ['required', 'string', 'max:2000'],
            'type'     => ['nullable', Rule::in(['general', 'complaint'])],
            // Honeypot field ضد البوتات — حقل مخفي، لو اتملى معناه بوت
            'website'  => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.max'  => 'الرسالة طويلة جدًا، الحد الأقصى 2000 حرف',
            'website.prohibited' => 'حدث خطأ، حاول مرة أخرى',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ip_address' => $this->ip(),
        ]);
    }
}