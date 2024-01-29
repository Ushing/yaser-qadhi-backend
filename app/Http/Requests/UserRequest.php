<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
                'name' => 'required',
                'password' => 'required',
                'email' => 'required|email',
            ];
        } else {
            return [
                'name' => ['required', 'unique:users,name,' . Request::segment(3)],
                'email' => 'required|email',
                'password' => 'required',
            ];
        }
    }
}
