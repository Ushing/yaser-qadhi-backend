<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if (Request::isMethod('post')) {
            return [
                'event_title' => 'required|max:255',
                'event_details' => 'required',
                'event_date' => 'required|date',
            ];
        } else {
            return [
                'event_title' => ['required', 'max:255', 'unique:events,event_title,' . Request::segment(3)],
                'status' => 'required',
                'event_details' => 'required',
                'event_date' => 'required|date',
            ];
        }
    }
}
