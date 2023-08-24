<?php

namespace App\Http\Requests\SysAdmin\Manage\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceDeptAdminRequest extends FormRequest
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
            'branch' => ['required'],
            'bu_department' => ['required'],
            'service_departments' => ['required'],
            'first_name' => ['required', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
            'suffix' => ['nullable', 'min:1', 'max:4'],
            'email' => [
                'required',
                'max:80',
                'email',
                Rule::unique('users')->ignore($this->serviceDeptAdmin)
            ]
        ];
    }
}