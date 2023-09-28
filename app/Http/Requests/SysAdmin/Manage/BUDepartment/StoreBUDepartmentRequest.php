<?php

namespace App\Http\Requests\SysAdmin\Manage\BUDepartment;

use Illuminate\Foundation\Http\FormRequest;

class StoreBUDepartmentRequest extends FormRequest
{
    protected $errorBag = 'storeBUDepartment';
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
            'name' => ['required', 'unique:departments,name'],
            'selectedBranches' => ['required', 'array']
        ];
    }

    public function messages()
    {
        return [
            'selectedBranch.required' => 'The branch field is required.'
        ];
    }
}