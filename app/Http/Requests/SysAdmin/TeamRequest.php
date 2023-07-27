<?php

namespace App\Http\Requests\SysAdmin;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'department' => ['required'],
            'name' => [
                'required',
                'unique:teams,name',
                function ($attribute, $value, $fail) {
                    $departments = Department::pluck('name')->toArray();

                    if (in_array($value, $departments)) {
                        $fail("The team name cannot be the same as any of the department names.");
                    }
                }
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Team name already exists. Try other team name.',
            'name.required' => 'Please provide a team name',
            'department.required' => 'Please select a department'
        ];
    }
}