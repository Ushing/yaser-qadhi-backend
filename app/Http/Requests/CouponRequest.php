<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
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
//                'code' => ['required', Rule::unique('coupons')],
                'validate_date' => 'required|date',
                'status' => 'required',
            ];
        } else {
            return [
                'code' => ['required', 'max:255', 'unique:coupons,code,' . Request::segment(3)],
                'validate_date' => 'required|date',
                'status' => 'required',
            ];
        }
    }
}
