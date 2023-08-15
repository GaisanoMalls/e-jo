<?php

namespace App\Http\Requests\ServiceDeptAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    protected $errorBag = 'editAnnouncement';
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
            'title' => ['required'],
            'department' => ['required'],
            'description' => ['required'],
            'is_draft' => ['boolean']
        ];
    }
}