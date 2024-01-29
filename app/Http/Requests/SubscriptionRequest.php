<?php

namespace App\Http\Requests;

use App\Rules\VideoFileTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SubscriptionRequest extends FormRequest
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
                'plan_name' => 'required|max:255',
                'plan_cost' => 'required|max:255',
                'duration' => 'required',
                'status' => 'required',
            ];
        } else {
            return [
                'plan_name' => ['required', 'max:255', 'unique:subscriptions,plan_name,' . Request::segment(3)],
                'plan_cost' => 'required|max:255',
                'duration' => 'required',
                'status' => 'required',
            ];
        }
    }
}
