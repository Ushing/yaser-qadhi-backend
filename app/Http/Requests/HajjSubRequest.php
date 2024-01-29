<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class HajjSubRequest extends FormRequest
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
                'title' => 'required',
                'checklist_id' => 'required',
                'status' => 'required',
            ];
        } else {
            return [
                'title' => ['required', 'unique:hajj_sublists,title,' . Request::segment(3)],
                'checklist_id' => 'required',
                'status' => 'required',
            ];
        }
    }
}
