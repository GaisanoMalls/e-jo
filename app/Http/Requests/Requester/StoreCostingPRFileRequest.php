<?php

namespace App\Http\Requests\Requester;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreCostingPRFileRequest extends FormRequest
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
            'costingPRFiles.*' => [
                'required',
                File::types(['pdf'])
                    ->max(25 * 1024) //25600 (25 MB)
            ]
        ];
    }

    public function messages()
    {
        return [
            'costingPRFiles.*.required' => 'Costing PR file is required',
            'costingPRFiles.*.file' => 'The uploaded file is not valid.',
            'costingPRFiles.*.mimes' => 'Invalid file type. File must be of type: pdf',
            'costingPRFiles.*.max' => 'The file size must not exceed 25 MB.'
        ];
    }
}
