<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'survey_id'          => ['required', 'exists:surveys,id'],
            'respondent_name'    => ['required', 'string', 'max:255'],
            'respondent_phone'   => ['required', 'string', 'regex:/^((009665|9665|\+9665|05|5)[0-9]{8}|(00201|201|\+201|01)[0125][0-9]{8})$/'],
            'answers'            => ['required', 'array'],
            'respondent_email'   => ['nullable', 'email', 'max:255'],
            'ip_address'         => ['nullable', 'ip'],
        ];
    }

    // يضيف الـ IP تلقائيًا قبل ما البيانات توصل للـ Controller
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ip_address' => $this->ip(),
        ]);
    }
}