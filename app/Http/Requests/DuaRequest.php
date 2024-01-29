<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DuaRequest extends FormRequest
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
                'dua_sub_category_id' => 'required|numeric',
                'status' => 'required',
               /*  'translation' => 'required',
                'transliteration' => 'required',
                'arabic_dua' => 'required', */

            ];
        } else {
            return [
                'title' => ['required', 'max:255', 'unique:duas,title,' . Request::segment(3)],
                'dua_sub_category_id' => 'required|numeric',
                'status' => 'required',
            ];
        }
    }
}
