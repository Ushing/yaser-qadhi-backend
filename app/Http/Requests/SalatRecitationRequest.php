<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SalatRecitationRequest extends FormRequest
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
                'status' => 'required',
                'title' => 'required',
            ];
        } else {
            return [
                'status' => 'required',
                'title' => 'required',
            ];
        }
    }
}
