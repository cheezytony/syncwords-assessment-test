<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'event_title' => 'nullable',
            'event_start_date' => 'nullable|date_format:Y-m-d H:i:s',
            'event_end_date' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }
}
