<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'rating' => ['required'],
            'had_issues_encountered' => ['required'],
            'description' => ['required'],
            'suggestion' => ['nullable'],
            'accepted_privacy_policy' => ['required', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'rating' => 'Please select a rating',
            'had_issues_encountered.required' => 'This field is required',
            'description.required' => 'You are required to provide a short feedback',
            'accepted_privacy_policy.required' => 'You must accept the Privacy Policy'
        ];
    }
}
