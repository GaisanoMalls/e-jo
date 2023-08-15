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
            'team' => ['required'],
            'sla' => ['required'],
            'subject' => ['required'],
            'description' => ['required'],
            'file_attachments.*' => [
                'nullable',
                File::types(['jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv'])
                    ->min(1024)
                    ->max(1 * 1024) //25600 (25 MB)
            ],
        ];
    }

    public function messages()
    {
        return [
            'team.required' => 'The team field is required. Please select a help topic.',
            'sla.required' => 'The SLA field is required. Please select a help topic.'
        ];
    }
}