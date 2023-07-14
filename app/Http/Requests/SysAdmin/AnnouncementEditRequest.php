<?php

namespace App\Http\Requests\SysAdmin;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementEditRequest extends FormRequest
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
            'edit_title' => ['required'],
            'edit_department' => ['required'],
            'edit_description' => ['required'],
        ];
    }
}
