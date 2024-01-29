<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LectureRequest extends FormRequest
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
                'title' => 'required|max:255',
                'lecture_sub_category_id' => 'required|numeric',
                'status' => 'required',
                'description' => 'required|min:3|max:1000',

            ];
        } else {
            return [
                'title' => ['required', 'max:255', 'unique:lectures,title,' . Request::segment(3)],
                'lecture_sub_category_id' => 'required|numeric',
                'status' => 'required',
                'description' => 'required|min:3|max:1000',
            ];
        }
    }
}
