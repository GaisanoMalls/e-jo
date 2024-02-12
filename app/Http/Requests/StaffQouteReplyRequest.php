<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StaffQouteReplyRequest extends FormRequest
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
            'quoteReplyDescription' => ['required'],
            'quoteReplyFiles.*' => [
                'nullable',
                File::types(['jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv'])
                    ->max(25 * 1024) //25600 (25 MB)
            ]
        ];
    }

    public function messages()
    {
        return [
            'quoteReplyDescription.required' => 'The description field is required.',
            'quoteReplyFiles.*.file' => 'The uploaded file is not valid.',
            'quoteReplyFiles.*.mimes' => 'Invalid file type. File must be of type: jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv',
            'quoteReplyFiles.*.max' => 'The file size must not exceed 25 MB.'
        ];
    }
}
