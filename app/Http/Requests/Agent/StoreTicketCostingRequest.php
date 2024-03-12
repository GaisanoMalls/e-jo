<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreTicketCostingRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:500'],
            'costingFiles.*' => [
                'required',
                File::types(['pdf'])->max(25 * 1024) //25600 (25 MB)
            ],
        ];
    }

    public function messages()
    {
        return [
            'costingFiles.*.file' => 'The uploaded file is not valid.',
            'costingFiles.*.mimes' => 'Invalid file type. File must be of type: jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv',
            'costingFiles.*.max' => 'The file size must not exceed 25 MB.',
        ];
    }
}
