<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SurahReciteFileRequest extends FormRequest
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
                'recite_language_id' => 'required',
                'surah_recitation_id' => 'required',
                'sub_title_file' => 'required',
                'translation' => 'nullable',
                'transliteration' => 'nullable',
            ];
        } else {
            return [

            ];
        }
    }

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            \Session::flash('recite_validation_error', 'Create Recite Files validation failed!');
        }

    }
}
