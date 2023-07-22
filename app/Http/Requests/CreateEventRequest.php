<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'event_title' => 'required',
            'event_start_date' => 'required|date_format:Y-m-d H:i:s',
            'event_end_date' => 'required|date_format:Y-m-d H:i:s',
        ];
    }
}
