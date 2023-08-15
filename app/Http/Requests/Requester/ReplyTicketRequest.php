<?php

namespace App\Http\Requests\Requester;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ReplyTicketRequest extends FormRequest
{
    protected $errorBag = 'requesterStoreTicketReply';
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
            'description' => ['required'],
            'replyFiles.*' => [
                'nullable',
                File::types(['jpeg, jpg, png, pdf, doc, docx, xlsx, xls, csv'])
                    ->min(1024)
                    ->max(25 * 1024) //25600 (25 MB)
            ]
        ];
    }
}