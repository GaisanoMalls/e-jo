<?php

namespace App\Http\Requests\SysAdmin;

use Illuminate\Foundation\Http\FormRequest;

class TeamBranchRequest extends FormRequest
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
            'team' => ['required'],
            'branch' => ['required']
        ];
    }
    
    public function messages()
    {
        return [
            'team.required' => 'Please select a team',
            'branch.required' => 'Please select a branch to assign this team.'
        ];
    }
}
