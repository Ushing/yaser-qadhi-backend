<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class QuranProgramRequest extends FormRequest
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
                'quran_program_category_id' => 'required|integer',
                'status' => 'required',
                'title' => 'required',
            ];
        } else {
            return [
                'quran_program_category_id' => 'required|integer',
                'status' => 'required',
                'title' => 'required',
            ];
        }
    }
}
