<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LectureSubCategoryRequest extends FormRequest
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
                'name' => 'required|max:255',
                'lecture_category_id' => 'required',

            ];
        } else {
            return [
                'name' => ['required', 'max:255', 'unique:lecture_sub_categories,name,' . Request::segment(3)],
                'lecture_category_id' => 'required',
                'status' => 'required',
            ];
        }
    }
}
