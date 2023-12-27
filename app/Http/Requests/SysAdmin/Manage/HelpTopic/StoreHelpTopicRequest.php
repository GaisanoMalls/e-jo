<?php

namespace App\Http\Requests\SysAdmin\Manage\HelpTopic;

use Illuminate\Foundation\Http\FormRequest;

class StoreHelpTopicRequest extends FormRequest
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
            'name' => ['required', 'unique:help_topics,name'],
            'sla' => ['required'],
            'service_department' => ['required'],
            'team' => ['nullable'],
            'amount' => ['nullable'],
            'teams' => '',
        ];
    }
}