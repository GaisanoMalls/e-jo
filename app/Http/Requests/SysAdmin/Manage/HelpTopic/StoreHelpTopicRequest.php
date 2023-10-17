<?php

namespace App\Http\Requests\SysAdmin\Manage\HelpTopic;

use Illuminate\Foundation\Http\FormRequest;

class StoreHelpTopicRequest extends FormRequest
{
    protected $errorBag = 'storeHelpTopic';
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
            'name' => ['required'],
            'sla' => ['required'],
            'service_department' => ['required'],
            'team' => ['nullable'],
            'level_of_approval' => ['nullable'],
        ];
    }
}