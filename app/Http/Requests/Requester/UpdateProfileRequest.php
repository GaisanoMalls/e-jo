<?php

namespace App\Http\Requests\Requester;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateProfileRequest extends FormRequest
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
            'suffix' => ['nullable', 'min:2', 'max:3'],
            'email' => ['required', 'email'],
            'mobile_number' => ['nullable', 'min:11', 'max:11'],
            'picture' => ['nullable', File::image()],
        ];
    }
}