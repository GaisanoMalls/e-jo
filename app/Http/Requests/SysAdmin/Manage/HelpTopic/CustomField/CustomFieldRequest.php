<?php

namespace App\Http\Requests\SysAdmin\Manage\HelpTopic\CustomField;

use Illuminate\Foundation\Http\FormRequest;

class CustomFieldRequest extends FormRequest
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
            'formName' => ['required', 'unique:forms,name'],
            'helpTopic' => ['required', 'numeric']
        ];
    }
}
