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
            'service_department' => ['required'],
            'team' => ['nullable'],
            'sla' => ['required'],
            'name' => ['required'],
            'level_of_approval' => ['required'],
        ];
    }
}