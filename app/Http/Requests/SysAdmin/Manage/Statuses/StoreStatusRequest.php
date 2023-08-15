<?php

namespace App\Http\Requests\SysAdmin\Manage\Statuses;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatusRequest extends FormRequest
{
    protected $errorBag = 'storeTicketStatus';
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
            'name' => ['required', 'unique:statuses,name'],
            'color' => ['required', 'unique:statuses,color']
        ];
    }
}