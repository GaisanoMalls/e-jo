<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'first_name' => ['required', 'min:2'],
            'middle_name' => ['nullable', 'min:1'],
            'last_name' => ['required', 'min:2'],
            'suffix' => ['nullable', 'min:2'],
            'email' => ['required', 'email'],
            'mobile_number' => ['nullable', 'min:11', 'max:11'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
