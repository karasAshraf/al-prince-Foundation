<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class EventRequest extends ActivityRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $eventId = $this->route('event')?->id;
        $rules['slug'] = ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($eventId)];
        return $rules;
    }
}
