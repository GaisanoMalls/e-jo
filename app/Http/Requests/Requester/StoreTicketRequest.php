<?php

namespace App\Http\Requests\Requester;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreTicketRequest extends FormRequest
{
    protected $errorBag = 'storeTicket';
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
            'service_department' => ['required'],
            'help_topic' => ['required'],
            'team' => ['nullable'],
            'sla' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'file_attachments.*' => [
                'nullable',
                File::types(['jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv'])
                    ->max(25 * 1024) //25600 (25 MB)
            ],
        ];
    }

    public function messages()
    {
        return [
            'team.required' => 'The team field is required. Please select a help topic.',
            'sla.required' => 'The SLA field is required. Please select a help topic.',
            'file_attachments.*.file' => 'The uploaded file is not valid.',
            'file_attachments.*.mimes' => 'Invalid file type. File must be of type: jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv',
            'file_attachments.*.max' => 'The file size must not exceed 25 MB.'
        ];
    }
}