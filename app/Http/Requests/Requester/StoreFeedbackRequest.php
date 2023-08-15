<?php

namespace App\Http\Requests\Requester;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    protected $errorBag = 'storeFeedback';
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
}