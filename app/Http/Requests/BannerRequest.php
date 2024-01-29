<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BannerRequest extends FormRequest
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
                'image' => 'required|image',

            ];
        } else {
            return [
                'title' => ['required', 'max:255', 'unique:message_banners,title,' . Request::segment(3)],
                'image' => 'required|image',
                'status' => 'required',

            ];
        }
    }
}
