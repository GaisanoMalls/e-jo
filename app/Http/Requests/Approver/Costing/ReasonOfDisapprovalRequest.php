<?php

namespace App\Http\Requests\Approver\Costing;

use Illuminate\Foundation\Http\FormRequest;

class ReasonOfDisapprovalRequest extends FormRequest
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
            'reasonOfDisapproval' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'reasonOfDisapproval.required' => 'Reason is required'
        ];
    }
}
