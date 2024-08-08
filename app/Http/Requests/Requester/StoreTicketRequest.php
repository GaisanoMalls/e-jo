<?php

namespace App\Http\Requests\Requester;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreTicketRequest extends FormRequest
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
            'serviceDepartment' => ['required'],
            'helpTopic' => ['required'],
            'team' => ['nullable'],
            'sla' => ['required'],
            'subject' => ['required'],
            'description' => ['nullable'],
            'priorityLevel' => ['required'],
            // 'fileAttachments.*' => [
            //     'nullable',
            //     File::types(['jpeg,jpg,png,pdf,doc,docx,xlsx,xls,csv,txt'])
            //         ->max(25600) //25600 (25 MB)
            // ],
            'helpTopicForm' => ''
        ];
    }

    public function messages()
    {
        return [
            'team.required' => 'The team field is required. Please select a help topic.',
            'sla.required' => 'The SLA field is required. Please select a help topic.',
            'priorityLevel.required' => 'Please select a priority level.',
            'fileAttachments.*.file' => 'The uploaded file is not valid.',
            'fileAttachments.*.mimes' => 'Invalid file type. File must be one of the following types: jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv',
            'fileAttachments.*.max' => 'The file size must not exceed to 25 MB.',
        ];
    }
}